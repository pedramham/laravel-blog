<?php

namespace Admin\ApiBolg\Providers;

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
