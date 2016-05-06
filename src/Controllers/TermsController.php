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


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TermsController extends BaseController {

	use DispatchesJobs, ValidatesRequests;

  protected $vocabulary;
  protected $route_prefix;

  public function __construct(Vocabulary $vocabulary) {
    $this->vocabulary = $vocabulary;
    $this->route_prefix = rtrim(Config::get('taxonomy::route_prefix'), '.') . '.';


    $layout = (object) [
      'extends' => config('taxonomy.config.layout.extends'),
      'header' => config('taxonomy.config.layout.header'),
      'content' => config('taxonomy.config.layout.content'),
    ];

    View::share('layout', $layout);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function getCreate($vocabulary_id) {
    return View::make('taxonomy::terms.create', compact('vocabulary_id'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function postStore() {
    $validation = Validator::make(Input::all(), Term::$rules);

    if ($validation->fails()) {
      return Redirect::back()
        ->withInput()
        ->withErrors($validation)
        ->with('error', 'There were validation errors.');
    }

    $vocabulary = Vocabulary::findOrFail(Input::get('vocabulary_id'));

    $term = \Taxonomy::createTerm($vocabulary->id, Input::get('name'));

    return Redirect::to(action('\Devfactory\Taxonomy\Controllers\TermsController@getIndex', ['id' => $vocabulary->id]))->with('success', 'Created');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function getEdit($id) {
    $term = Term::find($id);

    if (is_null($term)) {
      return Redirect::back($this->route_prefix . 'taxonomy.index');
    }

     return View::make('taxonomy::terms.edit', compact('term'));
  }

  public function getIndex() {
    $vocabulary = Vocabulary::findOrFail(Input::get('id'));
    $terms = $vocabulary->terms()->orderBy('parent', 'ASC')->orderBy('weight', 'ASC')->get();

    $ordered_terms = [];
    foreach ($terms as $term) {
      if (!$term->parent) {
        $ordered_terms[$term->id] = [
          'term' => $term,
          'children' => [],
        ];
      }
      else {
        $ordered_terms[$term->parent]['children'][] = $term;
      }
    }

    $terms = $ordered_terms;

    return view('taxonomy::terms.index', compact('vocabulary', 'terms'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function putUpdate($id) {
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

    return Redirect::to(action('\Devfactory\Taxonomy\Controllers\TermsController@getIndex', ['id' => $term->vocabulary_id]))->with('success', 'Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function deleteDestroy($id) {
    // Delete children if any exist
    Term::whereParent($id)->delete();

    // Delete Term
    Term::destroy($id);

    return Response::make('OK', 200);
  }

}
