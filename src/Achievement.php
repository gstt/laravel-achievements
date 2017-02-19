<?php

namespace Gstt\Achievements;

use Gstt\Achievements\Model\AchievementModel;
use Gstt\Achievements\Model\AchievementProgress;
use Illuminate\Database\Eloquent\Builder;

abstract class Achievement
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

    /**
     * Gets the model class for this achievement.
     *
     * @return AchievementModel
     */
    public function getModel()
    {
        $model = AchievementModel::where('class_name', self::class)->get();
        if (is_null($model)) {
            $model = new AchievementModel();
            $model->class_name = self::class;
        }

        // Updates the model with data from the achievement class
        $model->name        = $this->name;
        $model->description = $this->description;
        $model->points      = $this->points;

        // Saves
        $model->save();

        return $model;
    }

    /**
     * Statically call addProgressToAchiever.
     *
     * @param mixed $achiever The entity that will add progress to this achievement
     * @param int   $points   The amount of points to be added to this achievement
     */
    public static function addProgress($achiever, $points)
    {
        $instance = new (self::class)();
        $instance->addProgressToAchiever($achiever, $points);
    }

    /**
     * Statically call setProgressToAchiever.
     *
     * @param mixed $achiever The entity that will add progress to this achievement
     * @param int   $points   The amount of points to be added to this achievement
     */
    public static function setProgress($achiever, $points)
    {
        $instance = new (self::class)();
        $instance->addProgressToAchiever($achiever, $points);
    }

    /**
     * Adds a specified amount of points to the achievement.
     *
     * @param mixed $achiever The entity that will add progress to this achievement
     * @param int   $points   The amount of points to be added to this achievement
     */
    public function addProgressToAchiever($achiever, $points)
    {
        $progress = $this->getOrCreateProgressForAchiever($achiever);
        $progress->points = $progress->points + $points;
        $progress->save();
    }

    /**
     * Sets a specified amount of points to the achievement.
     *
     * @param mixed $achiever The entity that will add progress to this achievement
     * @param int   $points   The amount of points to be added to this achievement
     */
    public function setProgressToAchiever($achiever, $points)
    {
        $progress = $this->getOrCreateProgressForAchiever($achiever);
        $progress->points = $points;
        $progress->save();
    }

    /**
     * Gets the achiever's progress data for this achievement, or creates a new one if not existant
     * @param mixed|null $achiever
     *
     * @return AchievementProgress
     */
    public function getOrCreateProgressForAchiever($achiever)
    {
        $className = self::class;
        $progress = $achiever->achievements()->with(
            'details',
            function (Builder $query) use ($className) {
                $query->where('class_name', $className);
            }
        );
        if (is_null($progress)) {
            $progress = new AchievementProgress();
            $progress->details()->associate($this->getModel());
            $progress->achiever()->associate($achiever);

            $progress->save();
        }

        return $progress;
    }

    /**
     * Will be called when the achievement is unlocked.
     * @param $progress
     */
    public function whenUnlocked($progress)
    {
    }
}