<?php

namespace Gstt\Tests;

use Gstt\Achievements\Model\AchievementDetails;
use Gstt\Tests\Model\User;
use Gstt\Tests\Achievements\FirstPost;
use Gstt\Tests\Achievements\TenPosts;

class AchievementTest extends DBTestCase
{
    public $users;
    public $onePost;
    public $tenPosts;

    public function testSetup(){
        $this->users[] = User::find(1);
        $this->users[] = User::find(2);
        $this->users[] = User::find(3);
        $this->users[] = User::find(4);
        $this->users[] = User::find(5);

        $this->onePost = new FirstPost();
        $this->tenPosts = new TenPosts();

        // For users, check only names.
        // Achiever classes don't matter much. They just need to exist and have IDs.
        $this->assertEquals($this->users[0]->name, 'Gamer0');
        $this->assertEquals($this->users[1]->name, 'Gamer1');
        $this->assertEquals($this->users[2]->name, 'Gamer2');
        $this->assertEquals($this->users[3]->name, 'Gamer3');
        $this->assertEquals($this->users[4]->name, 'Gamer4');

        // Loads the AchievementDetails Models

        /** @var AchievementDetails $onePostDB */
        $onePostDB  = AchievementDetails::find(1);
        /** @var AchievementDetails $tenPostsDB */
        $tenPostsDB = AchievementDetails::find(2);

        // Compare Model data with class data for OnePost
        $this->assertNotNull($onePostDB);

        $this->assertEquals($onePostDB->name        , $this->onePost->name);
        $this->assertEquals($onePostDB->description , $this->onePost->description);
        $this->assertEquals($onePostDB->points      , $this->onePost->points);

        // Compare Model data with class data for TenPosts
        $this->assertNotNull($tenPostsDB);

        $this->assertEquals($tenPostsDB->name        , $this->tenPosts->name);
        $this->assertEquals($tenPostsDB->description , $this->tenPosts->description);
        $this->assertEquals($tenPostsDB->points      , $this->tenPosts->points);

        // Compares conversion between class instance and class data
        $this->assertEquals($onePostDB->getClass()->getClassName(), $this->onePost->getClassName());
        $this->assertEquals($tenPostsDB->getClass()->getClassName(), $this->tenPosts->getClassName());
    }
}