<?php

namespace Admin\ApiBolg\Providers;

use Admin\ApiBolg\Helper\FileHelper;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class ApiBlogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('fileHelper', function($app) {
            return new FileHelper();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadDatabase();
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
    //    dd(__DIR__.'/../../routes/api.php');
    }

    protected function loadDatabase(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
