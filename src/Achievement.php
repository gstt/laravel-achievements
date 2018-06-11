<?php

namespace Gstt\Achievements;

use Gstt\Achievements\Event\Unlocked as UnlockedEvent;
use Gstt\Achievements\Event\Progress as ProgressEvent;
use Gstt\Achievements\Model\AchievementDetails;
use Gstt\Achievements\Model\AchievementProgress;
use Illuminate\Database\Eloquent\Builder;

class Achievement
{

    /**
     * The unique identifier for the achievement.
     *
     * @var string
     */
    public $id;

    /*
     * The achievement name
     */
    public $name = "Achievement";

    /*
     * A small description for the achievement
     */
    public $description = "";

    /**
     * The amount of points required to unlock this achievement.
     */
    public $points = 1;

    /*
     * Whether this is a secret achievment or not.
     */
    public $secret = false;

    /**
     * Achievement constructor.
     * Should add the achievement to the database.
     */
    public function __construct($type)
    {
        $this->getModel($type);
    }

    /**
     * Gets the full class name.
     *
     * @return string
     */
    public function getClassName()
    {
        return static::class;
    }

    /**
     * Gets the amount of points needed to unlock the achievement.
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Gets the details class for this achievement.
     *
     * @return AchievementDetails
     */
    public function getModel($type)
    {
        $model = AchievementDetails::where('type', $type)->first();
        if (is_null($model)) {
            return 0;
        }

        return $model;
    }

    /**
     * Adds a specified amount of points to the achievement.
     *
     * @param mixed $achiever The entity that will add progress to this achievement
     * @param int   $points   The amount of points to be added to this achievement
     */
    public function addProgressToAchiever($achiever, $points = 1,$type)
    {
        $progress = $this->getOrCreateProgressForAchiever($achiever,$type);
        if (!$progress->isUnlocked()) {
            $progress->points = $progress->points + $points;
            $progress->type = $type;
            $progress->save();
        }
    }

    /**
     * Sets a specified amount of points to the achievement.
     *
     * @param mixed $achiever The entity that will add progress to this achievement
     * @param int   $points   The amount of points to be added to this achievement
     */
    public function setProgressToAchiever($achiever, $points,$type)
    {
        $progress = $this->getOrCreateProgressForAchiever($achiever,$type);

        if (!$progress->isUnlocked()) {
            $progress->points = $points;
            $progress->type = $type;
            $progress->save();
        }
    }

    /**
     * Gets the achiever's progress data for this achievement, or creates a new one if not existant
     * @param \Illuminate\Database\Eloquent\Model $achiever
     *
     * @return AchievementProgress
     */
    public function getOrCreateProgressForAchiever($achiever,$type)
    {
        $className = $this->getAchieverClassName($achiever);

        $achievementId = $this->getModel($type)->id;
        $progress = AchievementProgress::where('achiever_type', $className)
                                       ->where('achievement_id', $achievementId)
                                       ->where('achiever_id', $achiever->id)
                                       ->first();

        if (is_null($progress)) {
            $progress = new AchievementProgress();
            $progress->details()->associate($this->getModel($type));
            $progress->achiever()->associate($achiever);
            $progress->type = $type;
            $progress->save();
        }

        return $progress;
    }

    /**
     * Gets model morph name
     *
     * @param \Illuminate\Database\Eloquent\Model $achiever
     * @return string
     */
    protected function getAchieverClassName($achiever)
    {
        if ($achiever instanceof \Illuminate\Database\Eloquent\Model) {
            return $achiever->getMorphClass();
        }

        return get_class($achiever);
    }

    /**
     * Will be called when the achievement is unlocked.
     *
     * @param $progress
     */
    public function whenUnlocked($progress)
    {
    }

    /**
     * Will be called when progress is made on the achievement.
     *
     * @param $progress
     */
    public function whenProgress($progress)
    {
    }

    /**
     * Triggers the AchievementUnlocked Event.
     *
     * @param $progress
     */
    public function triggerUnlocked($progress)
    {
        event(new UnlockedEvent($progress));
        $this->whenUnlocked($progress);
    }

    /**
     * Triggers the AchievementProgress Event.
     *
     * @param $progress
     */
    public function triggerProgress($progress)
    {
        event(new ProgressEvent($progress));
        $this->whenProgress($progress);
    }
}
