<?php

namespace pierresilva\Modules\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $driver = ucfirst(config('modules.driver'));

        if ($driver == 'Custom') {
            $namespace = config('modules.custom_driver');
        } else {
            $namespace = 'pierresilva\Modules\Repositories\\'.$driver.'Repository';
        }

        $this->app->bind('pierresilva\Modules\Contracts\Repository', $namespace);
    }
}
