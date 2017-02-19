<?php

namespace Gstt\Achievements\Console;

use Illuminate\Console\GeneratorCommand;

/**
 * Creates an achievement class stub.
 *
 * @category Command
 * @package  Gstt\Achievements\Command
 * @author   Gabriel Simonetti <simonettigo@gmail.com>
 * @license  MIT License
 * @link     https://github.com/gstt/laravel-achievements
 */
class AchievementMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:achievement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new achievement class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Achievement';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/achievement_class.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace The root namespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Achievements';
    }
}
