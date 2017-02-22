<?php
namespace Gstt\Tests\Achievements;

use Gstt\Achievements\Achievement;

class TenPosts extends Achievement
{
    public $name = "10 Posts";
    public $description = "You have created 10 posts!";
    public $points = 10;
}