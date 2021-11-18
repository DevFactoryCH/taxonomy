<?php

namespace Devfactory\Taxonomy;

use Illuminate\Support\ServiceProvider;
use Devfactory\Taxonomy\Models\Vocabulary;
use Devfactory\Taxonomy\Models\Term;

class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
        $this->publishMigration();
        $this->publishAssets();
        $this->loadViewsFrom(__DIR__ . '/views', 'taxonomy');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'taxonomy');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['taxonomy'];
    }

    /**
     * Register the package services.
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->singleton('taxonomy', function ($app) {
            return new Taxonomy(new Vocabulary(), new Term());
        });
    }

    /**
     * Publish the package configuration
     */
    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('taxonomy.config.php'),
        ], 'config');
    }

    /**
     * Publish the migration stub
     */
    protected function publishMigration()
    {
        $this->publishes([
            __DIR__ . '/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');
    }

    protected function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../public/' => public_path('vendor/taxonomy'),
        ], 'public');
    }
}
