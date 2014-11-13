<?php namespace Devfactory\Taxonomy\Controllers;

use Config;
use Input;
use Lang;
use Redirect;
use Response;
use Sentry;
use Validator;
use View;
use Helpers;

use Devfactory\Taxonomy\Models\Vocabulary;
use Devfactory\Taxonomy\Models\Term;

class TaxonomyController extends \BaseController {

  protected $vocabulary;
  protected $route_prefix;

  public function __construct(Vocabulary $vocabulary) {
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

    return View::make('taxonomy::index', compact('vocabularies'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create() {
    return View::make('taxonomy::create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store() {
    $validation = Validator::make(Input::All(), Vocabulary::$rules);

    if ($validation->fails()) {
      return Redirect::route($this->route_prefix . 'taxonomy.create')
        ->withInput()
        ->withErrors($validation)
        ->with('message', 'There were validation errors.');
    }

    $vocabulary = Vocabulary::create([
      'name' => Input::get('name'),
    ]);

    $terms = preg_split('/[;,]/', trim(Input::get('terms')));
    foreach ($terms as $term) {
      if ($term != "") {
        $term = Term::create([
          'name' => $term,
          'vocabulary_id' => $vocabulary->id,
        ]);
      }
    }

    return Redirect::route($this->route_prefix . 'taxonomy.index');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id) {
    $vocabulary = $this->vocabulary->find($id);

    return View::make('taxonomy::show', compact('vocabulary'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id) {
    $vocabulary = $this->vocabulary->find($id);

    if (is_null($vocabulary)) {
      return Redirect::route($this->route_prefix . 'taxonomy.index');
    }

    $terms = implode(';', $vocabulary->terms->lists('name'));

    return View::make('taxonomy::edit', compact('vocabulary', 'terms'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id) {
    $validation = Validator::make(Input::All(), Vocabulary::$rules);

    if ($validation->fails()) {
      return Redirect::route($this->route_prefix . 'taxonomy.edit', $id)
        ->withInput()
        ->withErrors($validation)
        ->with('message', 'There were validation errors.');
    }

    $vocabulary = $this->vocabulary->find($id);
    $vocabulary->name = Input::get('name');
    $vocabulary->save();

    Term::where('vocabulary_id', $id)->delete();

    $terms = preg_split('/[;,]/', trim(Input::get('terms')));
    foreach ($terms as $term) {
      if (trim($term) != "") {
        $term = Term::create([
          'name' => $term,
          'vocabulary_id' => $vocabulary->id,
        ]);
      }
    }

    return Redirect::route($this->route_prefix . 'taxonomy.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id) {
    Vocabulary::destroy($id);

    return Response::make('OK', 200);
  }

}
