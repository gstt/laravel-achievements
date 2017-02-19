<?php

namespace Gstt\Achievements\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;

/**
 * Creates a migration for both tables required for achievements to work.
 *
 * @category Command
 * @package  Gstt\Achievements\Command
 * @author   Gabriel Simonetti <simonettigo@gmail.com>
 * @license  MIT License
 * @link     https://github.com/gstt/laravel-achievements
 */
class AchievementsTableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'achievements:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for bots achievements table';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Composer reference.
     *
     * @var Composer
     */
    protected $composer;

    /**
     * Create a new achievements table command instance.
     *
     * @param Filesystem $files    Filesystem reference
     * @param Composer   $composer Composer reference
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $fullPath = $this->createBaseMigration();

        $this->files->put($fullPath, $this->files->get(__DIR__ . '/stubs/achievements_tables.stub'));

        $this->info('Migration created successfully!');

        $this->composer->dumpAutoloads();
    }

    /**
     * Create a base migration file for the achievements.
     *
     * @return string
     */
    protected function createBaseMigration()
    {
        $name = 'create_achievements_table';

        $path = $this->laravel->databasePath().'/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }
}
