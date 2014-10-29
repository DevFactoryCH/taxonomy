<?php namespace Devfactory\Taxonomy;

use Illuminate\Support\ServiceProvider;
use Devfactory\Taxonomy\Models\Vocabulary;

class TaxonomyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = FALSE;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('devfactory/taxonomy', 'taxonomy', __DIR__);

		require __DIR__ . '/routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

    $this->app['taxonomy'] = $this->app->share(function($app) {
      return new Taxonomy();
    });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('taxonomy');
	}

}