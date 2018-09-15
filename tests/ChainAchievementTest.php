<?php

namespace Gstt\Tests;

use Gstt\Tests\AchievementChains\PostChain;
use Gstt\Tests\Achievements\FiftyPosts;
use Gstt\Tests\Achievements\FirstPost;
use Gstt\Tests\Achievements\TenPosts;
use Gstt\Tests\Model\User;

class ChainAchievementTest extends DBTestCase
{
    public $users;
    public $postChain;
    public $firstPost;
    public $tenPosts;
    public $fiftyPosts;

    public function setUp()
    {
        parent::setUp();
        $this->users[] = User::find(1);
        $this->users[] = User::find(2);
        $this->users[] = User::find(3);
        $this->users[] = User::find(4);
        $this->users[] = User::find(5);

        $this->postChain = new PostChain();
        $this->firstPost = new FirstPost();
        $this->tenPosts = new TenPosts();
        $this->fiftyPosts = new FiftyPosts();
    }

    /**
     * Test adding/removing/progressing on achievements.
     */
    public function testProgress()
    {
        $this->users[1]->addProgress($this->postChain, 9);
        $this->users[3]->addProgress($this->postChain, 49);
        $this->users[4]->addProgress($this->postChain, 51);

        // At the end of this setup:
        // $this->users[1] should have unlocked FirstPost
        // $this->users[3] should have unlocked FirstPost and TenPosts
        // $this->users[5] should have unlocked FirstPost, TenPosts and FiftyPosts
        // No user should have unlocked any other achievement other than that.

        $this->assertFalse($this->users[0]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[1]->hasUnlocked($this->firstPost));
        $this->assertFalse($this->users[2]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[3]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[4]->hasUnlocked($this->firstPost));

        $this->assertFalse($this->users[0]->hasUnlocked($this->tenPosts));
        $this->assertFalse($this->users[1]->hasUnlocked($this->tenPosts));
        $this->assertFalse($this->users[2]->hasUnlocked($this->tenPosts));
        $this->assertTrue($this->users[3]->hasUnlocked($this->tenPosts));
        $this->assertTrue($this->users[4]->hasUnlocked($this->tenPosts));

        $this->assertFalse($this->users[0]->hasUnlocked($this->fiftyPosts));
        $this->assertFalse($this->users[1]->hasUnlocked($this->fiftyPosts));
        $this->assertFalse($this->users[2]->hasUnlocked($this->fiftyPosts));
        $this->assertFalse($this->users[3]->hasUnlocked($this->fiftyPosts));
        $this->assertTrue($this->users[4]->hasUnlocked($this->fiftyPosts));

        // Right now, we add progress to users[1] and users[3]. They should unlock Ten and FiftyPosts respectively.
        // All other user achievement should remain unchanged.

        $this->users[1]->addProgress($this->postChain);
        $this->users[3]->addProgress($this->postChain);

        $this->assertFalse($this->users[0]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[1]->hasUnlocked($this->firstPost));
        $this->assertFalse($this->users[2]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[3]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[4]->hasUnlocked($this->firstPost));

        $this->assertFalse($this->users[0]->hasUnlocked($this->tenPosts));
        $this->assertTrue($this->users[1]->hasUnlocked($this->tenPosts));
        $this->assertFalse($this->users[2]->hasUnlocked($this->tenPosts));
        $this->assertTrue($this->users[3]->hasUnlocked($this->tenPosts));
        $this->assertTrue($this->users[4]->hasUnlocked($this->tenPosts));

        $this->assertFalse($this->users[0]->hasUnlocked($this->fiftyPosts));
        $this->assertFalse($this->users[1]->hasUnlocked($this->fiftyPosts));
        $this->assertFalse($this->users[2]->hasUnlocked($this->fiftyPosts));
        $this->assertTrue($this->users[3]->hasUnlocked($this->fiftyPosts));
        $this->assertTrue($this->users[4]->hasUnlocked($this->fiftyPosts));

        // Get the highest achievement on chain for each user.

        $this->assertEquals(null, $this->users[0]->highestOnAchievementChain($this->postChain));
        $this->assertEquals($this->tenPosts->name, $this->users[1]->highestOnAchievementChain($this->postChain)->details->name);
        $this->assertEquals(null, $this->users[2]->highestOnAchievementChain($this->postChain));
        $this->assertEquals($this->fiftyPosts->name, $this->users[3]->highestOnAchievementChain($this->postChain)->details->name);
        $this->assertEquals($this->fiftyPosts->name, $this->users[4]->highestOnAchievementChain($this->postChain)->details->name);

        // Sets user[0] points to 15.
        // Sets user[1] points to 5.
        // Sets user[4] points to 1.
        // Redo assertions.

        $this->users[0]->setProgress($this->postChain, 15);
        $this->users[1]->setProgress($this->postChain, 5);
        $this->users[4]->setProgress($this->postChain, 1);

        $this->assertTrue($this->users[0]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[1]->hasUnlocked($this->firstPost));
        $this->assertFalse($this->users[2]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[3]->hasUnlocked($this->firstPost));
        $this->assertTrue($this->users[4]->hasUnlocked($this->firstPost));

        $this->assertTrue($this->users[0]->hasUnlocked($this->tenPosts));
        $this->assertTrue($this->users[1]->hasUnlocked($this->tenPosts));
        $this->assertFalse($this->users[2]->hasUnlocked($this->tenPosts));
        $this->assertTrue($this->users[3]->hasUnlocked($this->tenPosts));
        $this->assertTrue($this->users[4]->hasUnlocked($this->tenPosts));

        $this->assertFalse($this->users[0]->hasUnlocked($this->fiftyPosts));
        $this->assertFalse($this->users[1]->hasUnlocked($this->fiftyPosts));
        $this->assertFalse($this->users[2]->hasUnlocked($this->fiftyPosts));
        $this->assertTrue($this->users[3]->hasUnlocked($this->fiftyPosts));
        $this->assertTrue($this->users[4]->hasUnlocked($this->fiftyPosts));
    }
}