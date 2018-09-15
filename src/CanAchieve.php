<?php
/**
 * @author Gabriel Simonetti
 */
namespace Gstt\Achievements;

interface CanAchieve
{
    // Adds an specified amount of points of progress
    public function addProgressToAchiever($achiever, $points);

    // Sets the specified amount of points to this achiever
    public function setProgressToAchiever($achiever, $points);
}