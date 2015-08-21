<?php namespace Devfactory\Taxonomy;

use Illuminate\Support\ServiceProvider;
use Devfactory\Taxonomy\Models\Vocabulary;
use Devfactory\Taxonomy\Models\Term;

class TaxonomyServiceProvider extends ServiceProvider {

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
  public function boot() {
    $this->publishConfig();
    $this->publishMigration();
    $this->loadViewsFrom(__DIR__ . '/views', 'taxonomy');
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register() {
    $this->registerServices();
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides() {
    return ['taxonomy'];
  }

	/**
   * Register the package services.
   *
   * @return void
   */
  protected function registerServices() {
    $this->app->bindShared('taxonomy', function ($app) {
      return new Taxonomy(new Vocabulary(), new Term());
    });
  }

  /**
   * Publish the package configuration
   */
  protected function publishConfig() {
    $this->publishes([
      __DIR__ . '/config/config.php' => config_path('taxonomy.config.php'),
    ]);
  }

  /**
   * Publish the migration stub
   */
  protected function publishMigration() {
    $this->publishes([
      __DIR__ . '/migrations' => base_path('database/migrations')
    ]);
  }

}