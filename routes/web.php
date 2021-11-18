<?php

use Devfactory\Taxonomy\Controllers;;

$prefix = config('taxonomy.config.route_prefix');

Route::prefix($prefix)->group(function() use ($prefix) {
    Route::resource('taxonomy', TaxonomyController::class);
    Route::post('taxonomy/{id}/order', [TaxonomyController::class, 'orderTerms'])
        ->name($prefix . '.taxonomy.order.terms');
    Route::resource('terms', TermsController::class);
});
