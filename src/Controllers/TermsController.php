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

class TermsController extends \BaseController {

  protected $vocabulary;
  protected $route_prefix;

  public function __construct(Vocabulary $vocabulary) {
    parent::__construct();

    $this->vocabulary = $vocabulary;
    $this->route_prefix = rtrim(Config::get('taxonomy::route_prefix'), '.') . '.';

    View::composer('taxonomy::*', 'Devfactory\Taxonomy\Composers\TaxonomyComposer');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create() {
    $vocabulary_id = Session::get('vocabulary_id');

    return View::make('taxonomy::terms.create', compact('vocabulary_id'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store() {
    $validation = Validator::make(Input::all(), Term::$rules);

    if ($validation->fails()) {
      return Redirect::back()
        ->withInput()
        ->withErrors($validation)
        ->with('error', 'There were validation errors.');
    }

    $vocabulary = Vocabulary::findOrFail(Input::get('vocabulary_id'));

    $term = \Taxonomy::createTerm($vocabulary->id, Input::get('name'));

    return Redirect::route($this->route_prefix . 'taxonomy.edit', $vocabulary->id);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id) {
    $term = Term::find($id);

    if (is_null($term)) {
      return Redirect::back($this->route_prefix . 'taxonomy.index');
    }

    return View::make('taxonomy::terms.edit', compact('term'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id) {
    $validation = Validator::make(Input::all(), Term::$rules);

    if ($validation->fails()) {
      return Redirect::back()
        ->withInput()
        ->withErrors($validation)
        ->with('error', 'There were validation errors.');
    }

    $term = Term::find($id);
    $term->name = Input::get('name');
    $term->save();

    return Redirect::route($this->route_prefix . 'taxonomy.edit', $term->vocabulary->id);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id) {
    Term::destroy($id);

    return Response::make('OK', 200);
  }

}
