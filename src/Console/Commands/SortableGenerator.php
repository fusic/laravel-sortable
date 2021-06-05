<?php

namespace Sortable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class SortableGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sortable {name} {--f|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new sortable class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Sortable';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Sort';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__ . '/Stubs/Sortable.stab';
    }
}
