<?php

namespace pierresilva\Modules\Console\Commands;

use Illuminate\Console\Command;
use pierresilva\Modules\Facades\Module;
use pierresilva\Modules\RepositoryManager;
use pierresilva\Modules\Repositories\Repository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleTestCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run test for a specific module';

    /**
     * @var RepositoryManager
     */
    protected $module;

    /**
     * Create a new command instance.
     *
     * @param RepositoryManager $module
     */
    public function __construct(RepositoryManager $module)
    {
        parent::__construct();

        $this->module = $module;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $repository = modules(config('modules.default_location'));

        $slug = $this->argument('slug');

        $module = Module::where('slug', $slug);

        if ($slug) {
            if (!$repository->exists($slug)) {
                return $this->error('Module does not exist.');
            }

            if ($repository->isEnabled($slug)) {
                $this->runModuleTest($slug, $repository, $module);
            } elseif ($this->option('force')) {
                $this->runModuleTest($slug, $repository, $module);
            }
        }

    }

    private function runModuleTest($slug, $repository, $module) {
        $this->info('Running Feature tests');
        $this->info(shell_exec('php vendor/phpunit/phpunit/phpunit --testsuite=' . $module['name'] . 'Feature'));

        if ($this->option('unit')) {
            $this->info('Running Unit tests');
            $this->info(shell_exec('php vendor/phpunit/phpunit/phpunit --testsuite=' . $module['name'] . 'Unit'));
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [['slug', InputArgument::REQUIRED, 'Module slug.']];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['unit', null, InputOption::VALUE_NONE, 'Runs unit tests.'],
            ['location', null, InputOption::VALUE_OPTIONAL, 'Which modules location to use.'],
        ];
    }

}
