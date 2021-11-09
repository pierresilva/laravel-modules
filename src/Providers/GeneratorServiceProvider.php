<?php

namespace pierresilva\Modules\Providers;

use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the provided services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the provided services.
     */
    public function register()
    {
        $generators = [
            'command.make.module'            => \pierresilva\Modules\Console\Generators\MakeModuleCommand::class,
            'command.make.module.controller' => \pierresilva\Modules\Console\Generators\MakeControllerCommand::class,
            'command.make.module.middleware' => \pierresilva\Modules\Console\Generators\MakeMiddlewareCommand::class,
            'command.make.module.migration'  => \pierresilva\Modules\Console\Generators\MakeMigrationCommand::class,
            'command.make.module.model'      => \pierresilva\Modules\Console\Generators\MakeModelCommand::class,
            'command.make.module.policy'     => \pierresilva\Modules\Console\Generators\MakePolicyCommand::class,
            'command.make.module.provider'   => \pierresilva\Modules\Console\Generators\MakeProviderCommand::class,
            'command.make.module.request'    => \pierresilva\Modules\Console\Generators\MakeRequestCommand::class,
            'command.make.module.seeder'     => \pierresilva\Modules\Console\Generators\MakeSeederCommand::class,
            'command.make.module.test'       => \pierresilva\Modules\Console\Generators\MakeTestCommand::class,
            'command.make.module.job'        => \pierresilva\Modules\Console\Generators\MakeJobCommand::class,
        ];

        foreach ($generators as $slug => $class) {
            $this->app->singleton($slug, function ($app) use ($slug, $class) {
                return $app[$class];
            });

            $this->commands($slug);
        }
    }
}
