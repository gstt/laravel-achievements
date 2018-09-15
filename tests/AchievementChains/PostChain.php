<?php

namespace Gstt\Tests\AchievementChains;

use Gstt\Tests\Achievements\FirstPost;
use Gstt\Tests\Achievements\TenPosts;
use Gstt\Tests\Achievements\FiftyPosts;

use Gstt\Achievements\AchievementChain;

class PostChain extends AchievementChain
{
    public function chain()
    {
        return [new FirstPost(), new TenPosts(), new FiftyPosts()];
    }
}