<?php
/**
 * Created by PhpStorm.
 * User: Gabriel Simonetti
 * Date: 18/02/2017
 * Time: 21:03
 */

namespace Gstt\Achievements;

use Gstt\Achievements\Model\AchievementDetails;
use Gstt\Achievements\Model\AchievementProgress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait EntityRelationsAchievements
{
    /**
     * Get the entity's Achievements
     *
     * @return Builder
     */
    public function achievements()
    {
        if (config('achievements.locked_sync')) {
            $this->syncAchievements();
        }
        return $this->morphMany(AchievementProgress::class, 'achiever')
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Retrieves the status for the specified achievement
     * @param Achievement $achievement
     * @return AchievementProgress
     */
    public function achievementStatus(Achievement $achievement)
    {
        return $this->achievements()->where('achievement_id', $achievement->getModel()->id)->first();
    }

    /**
     * Return true if the user has unlocked this achievement, false otherwise.
     * @param  Achievement $achievement
     * @return bool
     */
    public function hasUnlocked(Achievement $achievement)
    {
        $status = $this->achievementStatus($achievement);
        if (is_null($status) || is_null($status->unlocked_at)) {
            return false;
        }
        return true;
    }

    /**
     * Get the entity's achievements in progress.
     *
     * @return Collection
     */
    public function inProgressAchievements()
    {
        return $this->achievements()->whereNull('unlocked_at')->where('points', '>', 0)->get();
    }

    /**
     * Get the entity's unlocked achievements.
     *
     * @return Collection
     */
    public function unlockedAchievements()
    {
        return $this->achievements()->whereNotNull('unlocked_at')->get();
    }

    /**
     * Get the entity's locked achievements.
     */
    public function lockedAchievements()
    {
        if (config('achievements.locked_sync')) {
            // Relationships should be synced. Just return relationship data.
            return $this->achievements()->whereNull('unlocked_at')->get();
        } else {
            // Query all unsynced
            $unsynced = AchievementDetails::getUnsyncedByAchiever($this)->get();
            $self = $this;
            $unsynced = $unsynced->map(function ($el) use ($self) {
                $progress = new AchievementProgress();
                $progress->details()->associate($el);
                $progress->achiever()->associate($this);
                $progress->points = 0;
                $progress->created_at = null;
                $progress->updated_at = null;
                return $progress;
            });

            // Merge with progressed, but not yet unlocked
            $lockedProgressed = $this->achievements()->whereNull('unlocked_at')->get();
            $locked = $lockedProgressed->merge($unsynced);

            return $locked;
        }
    }

    /**
     * Syncs achievement data.
     */
    public function syncAchievements()
    {
        /** @var Collection $locked */
        $locked = AchievementDetails::getUnsyncedByAchiever($this);
        $self = $this;
        $locked->each(function ($el) use ($self) {
            $progress = new AchievementProgress();
            $progress->details()->associate($el);
            $progress->achiever()->associate($this);
            $progress->points = 0;
            $progress->save();
        });
    }
}