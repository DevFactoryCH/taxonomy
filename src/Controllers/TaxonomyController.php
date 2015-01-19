<?php namespace Devfactory\Taxonomy\Controllers;

use Config;
use Input;
use Lang;
use Redirect;
use Response;
use Sentry;
use Session;
use Validator;
use View;
use Helpers;

use Devfactory\Taxonomy\Models\Vocabulary;
use Devfactory\Taxonomy\Models\Term;

class TaxonomyController extends \BaseController {

  protected $vocabulary;
  protected $route_prefix;

  public function __construct(Vocabulary $vocabulary) {
    parent::__construct();

    $this->vocabulary = $vocabulary;
    $this->route_prefix = rtrim(Config::get('taxonomy::route_prefix'), '.') . '.';

    View::composer('taxonomy::*', 'Devfactory\Taxonomy\Composers\TaxonomyComposer');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index() {
    $vocabularies = $this->vocabulary->paginate(10);

    return View::make('taxonomy::vocabulary.index', compact('vocabularies'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id) {
    $vocabulary = $this->vocabulary->find($id);

    Session::put('vocabulary_id', $vocabulary->id);

    if (is_null($vocabulary)) {
      return Redirect::route($this->route_prefix . 'taxonomy.index');
    }

    $terms = $vocabulary->terms;

    return View::make('taxonomy::vocabulary.edit', compact('vocabulary', 'terms'));
  }

}
