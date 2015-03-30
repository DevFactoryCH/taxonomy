<?php
$route_enabled = (bool) Config::get('taxonomy::route_enabled', true);
if ($route_enabled) {
  $prefix = Config::get('taxonomy::route_prefix');
  $route_before = Config::get('taxonomy::route_before', []);

  Route::group(array('prefix' => $prefix, 'before' => $route_before), function() use ($prefix) {

    Route::resource('taxonomy', 'Devfactory\Taxonomy\Controllers\TaxonomyController');

    Route::post('taxonomy/{id}/order', array(
      'as' => $prefix .'.taxonomy.order.terms',
      'uses' => 'Devfactory\Taxonomy\Controllers\TaxonomyController@orderTerms',
    ));

    Route::resource('terms', 'Devfactory\Taxonomy\Controllers\TermsController');

  });
}
