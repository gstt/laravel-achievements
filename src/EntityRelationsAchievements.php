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

trait EntityRelationsAchievements
{
    /**
     * Get the entity's Achievements
     *
     * @return Builder
     */
    public function achievements()
    {
        return $this->morphMany(AchievementProgress::class, 'achiever')
            ->orderBy('created_at', 'desc');
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
        if(is_null($status) || is_null($status->unlocked_at)){
            return false;
        }
        return true;
    }

    /**
     * Get the entity's achievements in progress.
     *
     * @return Builder
     */
    public function inProgressAchievements()
    {
        return $this->achievements()->whereNull('unlocked_at')->get();
    }

    /**
     * Get the entity's unlocked achievements.
     *
     * @return Builder
     */
    public function unlockedAchievements()
    {
        return $this->achievements()->whereNotNull('unlocked_at')->get();
    }
}