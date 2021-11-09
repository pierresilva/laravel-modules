<?php

namespace pierresilva\Modules\Console\Generators;

use Symfony\Component\Console\Input\InputOption;
use pierresilva\Modules\Console\GeneratorCommand;

class MakeControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make:controller
    	{slug : The slug of the module}
    	{name : The name of the controller class}
    	{--resource : Generate a module resource controller class}
    	{--location= : The modules location to create the module controller class in}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module controller class';

    /**
     * String to store the command type.
     *
     * @var string
     */
    protected $type = 'Module controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('resource')) {
            return __DIR__ . '/stubs/controller.resource.stub';
        }

        return __DIR__ . '/stubs/controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     * @throws \pierresilva\Modules\Exceptions\ModuleNotFoundException
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return module_class($this->argument('slug'), 'Http\\Controllers', $this->option('location'));
    }
}
