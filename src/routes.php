<?php

$prefix = config('taxonomy.config.route_prefix');

Route::group(array('prefix' => $prefix), function() {

  Route::resource('taxonomy', 'Devfactory\Taxonomy\Controllers\TaxonomyController');

  Route::resource('terms', 'Devfactory\Taxonomy\Controllers\TermsController');

});
