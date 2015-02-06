<?php

$prefix = Config::get('taxonomy::route_prefix');

Route::group(array('prefix' => $prefix), function() use ($prefix) {

  Route::resource('taxonomy', 'Devfactory\Taxonomy\Controllers\TaxonomyController');

  Route::post('taxonomy/{id}/order', array(
    'as' => $prefix .'.taxonomy.order.terms',
    'uses' => 'Devfactory\Taxonomy\Controllers\TaxonomyController@orderTerms',
  ));

  Route::resource('terms', 'Devfactory\Taxonomy\Controllers\TermsController');

});
