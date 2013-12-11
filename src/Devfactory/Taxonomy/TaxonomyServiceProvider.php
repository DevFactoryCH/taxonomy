<?php namespace Devfactory\Taxonomy;

use Illuminate\Support\ServiceProvider;

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
	public function boot()
	{
		$this->package('devfactory/taxonomy');
		$this->package('devfactory/taxonomy/config/config.php');
		include __DIR__.'/../../routes.php';
		
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['taxonomy'] = $this->app->share(function($app)
		{
		    return new Taxonomy;
		});

		$this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('Taxonomy', 'Devfactory\Taxonomy\TaxonomyFacade');
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