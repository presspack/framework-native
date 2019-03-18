<?php

namespace Presspack\Framework\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Presspack\Framework\Support\Localize;
use Presspack\Framework\Post;

class ServiceProvider extends BaseServiceProvider
{
    protected $commands = [
        'Presspack\Framework\Commands\SaltsGenerate',
        'Presspack\Framework\Commands\MakeCustomPostType',
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/presspack.php' => config_path('presspack.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->commands($this->commands);
        
        $this->app->singleton('Presspack\Framework\Localize', function ($app) {
            return new Post();
        });
        
        $this->app->singleton('Presspack\Framework\Localize', function ($app) {
            return new Localize();
        });

        $this->mergeConfigFrom(__DIR__.'/../../config/presspack.php', 'presspack');
    }
}
