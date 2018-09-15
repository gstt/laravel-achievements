<?php

namespace Gstt\Achievements;

use Gstt\Achievements\Model\AchievementProgress;

trait RoutesAchievements
{

    /**
     * Adds a specified amount of points to the achievement.
     *
     * @param CanAchieve $instance An instance of an achievement
     * @param mixed $points   The amount of points to add to the achievement's progress
     *
     * @return void
     */
    public function addProgress(CanAchieve $instance, $points = 1)
    {
        $instance->addProgressToAchiever($this, $points);
    }

    /**
     * Removes a specified amount of points from the achievement.
     *
     * @param CanAchieve $instance An instance of an achievement
     * @param mixed $points   The amount of points to remove from the achievement's progress
     */
    public function removeProgress(CanAchieve $instance, $points = 1)
    {
        $this->addProgress($instance, (-1 * $points));
    }

    /**
     * Sets the current progress as the specified amount of points.
     *
     * @param CanAchieve $instance An instance of an achievement
     * @param mixed $points   The amount of points to remove from the achievement's progress
     */
    public function setProgress(CanAchieve $instance, $points)
    {
        $instance->setProgressToAchiever($this, $points);
    }

    /**
     * Resets the achievement's progress, setting the points to 0.
     *
     * @param mixed $instance An instance of an achievement
     *
     * @return void
     */
    public function resetProgress($instance)
    {
        $this->setProgress($instance, 0);
    }


    /**
     * Unlocks an achievement
     *
     * @param mixed $instance An instance of an achievement
     *
     * @return void
     */
    public function unlock($instance)
    {
        $this->setProgress($instance, $instance->points);
    }

    /**
     * Gets the highest achievement unlocked on a specific achievement chain.
     * @param AchievementChain $chain
     * @return null|AchievementProgress
     */
    public function highestOnAchievementChain(AchievementChain $chain)
    {
        return $chain->highestOnChain($this);
    }
}
