<?php

$prefix = Config::get('taxonomy::route_prefix');

#voc management
Route::get($prefix.'/vocabularies', 'Devfactory\Taxonomy\VocabulariesController@index');
Route::get($prefix.'/vocabularies/create', 'Devfactory\Taxonomy\VocabulariesController@create');
Route::post($prefix.'/vocabularies/create', 'Devfactory\Taxonomy\VocabulariesController@store');
Route::get($prefix.'/vocabularies/show/{id}', 'Devfactory\Taxonomy\VocabulariesController@show');
Route::get($prefix.'/vocabularies/delete/{id}', 'Devfactory\Taxonomy\VocabulariesController@destroy');

Route::get($prefix.'/vocabularies/edit/{id}', 'Devfactory\Taxonomy\VocabulariesController@edit');
Route::post($prefix.'/vocabularies/edit/{id}', 'Devfactory\Taxonomy\VocabulariesController@update');

