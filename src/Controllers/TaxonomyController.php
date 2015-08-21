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

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TaxonomyController extends BaseController {

	use DispatchesCommands, ValidatesRequests;


  protected $vocabulary;
  protected $route_prefix;

  public function __construct(Vocabulary $vocabulary) {
    $this->vocabulary = $vocabulary;
    $this->route_prefix = rtrim(config('taxonomy.route_prefix'), '.') . '.';

    $layout = (object) [
      'extends' => config('taxonomy.config.layout.extends'),
      'header' => config('taxonomy.config.layout.header'),
      'content' => config('taxonomy.config.layout.content'),
    ];

    View::share('layout', $layout);
  }

  public function index() {
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getIndex() {
    $vocabularies = $this->vocabulary->paginate(10);

    return view('taxonomy::vocabulary.index', [ 'vocabularies' => $vocabularies]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function getEdit($id) {
    $vocabulary = $this->vocabulary->find($id);

    Session::put('vocabulary_id', $vocabulary->id);

    if (is_null($vocabulary)) {
      return Redirect::route($this->route_prefix . 'taxonomy.index');
    }

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

    return View::make('taxonomy::vocabulary.edit', compact('vocabulary', 'terms'));
  }

  public function postOrderTerms($id) {
    $this->vocabulary->find($id);

    $request = \Request::instance();
    $json = $request->getContent();
    $content = json_decode($json);

    foreach ($content as $parent_key => $parent){
      $parent_term = Term::find($parent->id);

      $parent_term->parent = 0;
      $parent_term->weight = $parent_key;
      $parent_term->save();

      if (empty($parent->children)) {
        continue;
      }

      foreach ($parent->children as $child_key => $child){
        $child_term = Term::find($child->id);

        $child_term->parent = $parent_term->id;
        $child_term->weight = $child_key;

        $child_term->save();
      }
    }

  }

}
