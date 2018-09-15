<?php
/**
 * @author Gabriel Simonetti
 * @created_at Setembro 15, 2018
 */

namespace Gstt\Achievements;

use Gstt\Achievements\Model\AchievementProgress;

abstract class AchievementChain implements CanAchieve
{

    /**
     * Expects an array of Achievements.
     * @return Achievement[]
     */
    abstract function chain();

    /**
     * For an Achiever, return the highest achievement on the chain that is unlocked.
     * @param $achiever
     * @return null|AchievementProgress
     */
    public function highestOnChain($achiever)
    {
        $latestUnlocked = null;
        foreach($this->chain() as $instance) {
            /** @var Achievement $instance */
            /** @var Achiever $achiever */
            if($achiever->hasUnlocked($instance)){
                $latestUnlocked = $achiever->achievementStatus($instance);
            } else {
                return $latestUnlocked;
            }
        }
        return $latestUnlocked;
    }

    public function addProgressToAchiever($achiever, $points)
    {
        foreach ($this->chain() as $instance) {
            /** @var Achievement $instance */
            $instance->addProgressToAchiever($achiever, $points);
        }
    }

    public function setProgressToAchiever($achiever, $points)
    {
        foreach ($this->chain() as $instance) {
            /** @var Achievement $instance */
            $instance->setProgressToAchiever($achiever, $points);
        }
    }
}