<?php

namespace Gstt\Tests;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Artisan;
use Gstt\Tests\Model\User;

class DBTestCase extends TestCase
{
    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';
        $app->register('Gstt\Achievements\AchievementsServiceProvider');
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }

    /**
     * Setup DB before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');

        Artisan::call('migrate');
        $this->seedUsers();
    }

    public function seedUsers()
    {
        User::create(['name' => 'Gamer0', 'email' => 'gamer0@email.com', 'password' => '111111']);
        User::create(['name' => 'Gamer1', 'email' => 'gamer1@email.com', 'password' => '111111']);
        User::create(['name' => 'Gamer2', 'email' => 'gamer2@email.com', 'password' => '111111']);
        User::create(['name' => 'Gamer3', 'email' => 'gamer3@email.com', 'password' => '111111']);
        User::create(['name' => 'Gamer4', 'email' => 'gamer4@email.com', 'password' => '111111']);
    }

}