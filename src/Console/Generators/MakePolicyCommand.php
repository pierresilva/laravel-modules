<?php

namespace pierresilva\Modules\Console\Generators;

use pierresilva\Modules\Console\GeneratorCommand;

class MakePolicyCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make:policy
    	{slug : The slug of the module.}
    	{name : The name of the policy class.}
    	{--location= : The modules location to create the module policy class in}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module policy class';

    /**
     * String to store the command type.
     *
     * @var string
     */
    protected $type = 'Module policy';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/policy.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return module_class($this->argument('slug'), 'Policies', $this->option('location'));
    }
}
