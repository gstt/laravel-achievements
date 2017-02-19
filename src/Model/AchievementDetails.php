<?php

namespace Gstt\Achievements\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Model for the table that will store the details for an Achievement Progress.
 *
 * @category Model
 * @package  Gstt\Achievements\Model
 * @author   Gabriel Simonetti <simonettigo@gmail.com>
 * @license  MIT License
 * @link     https://github.com/gstt/laravel-achievements
 */
class AchievementDetails extends Model
{

    public $secret = false;
    /**
     * Return all users that have made progress on this achievement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progress()
    {
        return $this->hasMany('Gstt\Achievements\Model\AchievementProgress', 'achievement_id');
    }

    /**
     * Return the progress data for achievers that have unlocked this achievement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unlocks()
    {
        return $this->progress()->whereNotNull('unlocked_at')->get();
    }

    /**
     * Returns the class that defined this achievement.
     */
    public function getClass()
    {
        return new $this->class_name();
    }
}