<?php
namespace Gstt\Tests\Achievements;

use Gstt\Achievements\Achievement;

class FiftyPosts extends Achievement
{
    public $name = "Fifty Posts";
    public $description = "You have created 50 posts!";
    public $points = 50;
}
