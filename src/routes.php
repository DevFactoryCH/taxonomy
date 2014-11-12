<?php

$prefix = Config::get('taxonomy::route_prefix');

Route::group(array('prefix' => 'admin'), function() {

  Route::resource('taxonomy', 'Devfactory\Taxonomy\Controllers\TaxonomyController');

});
