<?php

namespace Gstt\Achievements;

trait RoutesAchievements
{

    /**
     * Adds a specified amount of points to the achievement.
     *
     * @param mixed $instance An instance of an achievement
     * @param mixed $points   The amount of points to add to the achievement's progress
     *
     * @return void
     */
    public function addProgress($instance, $points)
    {
        $instance->addProgressToAchiever($this, $points);
    }

    /**
     * Removes a specified amount of points from the achievement.
     *
     * @param mixed $instance An instance of an achievement
     * @param mixed $points   The amount of points to remove from the achievement's progress
     */
    public function removeProgress($instance, $points)
    {
        $this->addProgress($instance, (-1 * $points));
    }

    /**
     * Sets the current progress as the speficied amount of points.
     *
     * @param mixed $instance An instance of an achievement
     * @param mixed $points   The amount of points to remove from the achievement's progress
     */
    public function setProgress($instance, $points)
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
}