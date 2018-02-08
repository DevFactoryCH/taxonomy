<?php namespace Devfactory\Taxonomy\Controllers;

use Illuminate\Http\Request;

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
use Devfactory\Taxonomy\Models\TermRelation;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TaxonomyController extends BaseController {

	use DispatchesCommands, ValidatesRequests;


  protected $vocabulary;
  protected $route_prefix;

  public function __construct(Vocabulary $vocabulary) {
    $this->vocabulary = $vocabulary;
    $this->route_prefix = rtrim(config('taxonomy.config.route_prefix'), '.') . '.';

    $layout = (object) [
      'extends' => config('taxonomy.config.layout.extends'),
      'header' => config('taxonomy.config.layout.header'),
      'content' => config('taxonomy.config.layout.content'),
    ];

    View::share('layout', $layout);
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
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getCreate() {
    return view('taxonomy::vocabulary.create');
  }

  public function postStore(Request $request) {
    $this->validate($request, isset($this->vocabulary->rules_create) ? $this->vocabulary->rules_create : $this->vocabulary->rules);

    Vocabulary::create(Input::only('name'));

    return Redirect::to(action('\Devfactory\Taxonomy\Controllers\TaxonomyController@getIndex'))->with('success', 'Created');

  }

  /**
   * Destory a resource.
   *
   * @return Response
   */
  public function deleteDestroy($id) {
    $vocabulary = $this->vocabulary->find($id);

    $terms = $vocabulary->terms->lists('id')->toArray();

    TermRelation::whereIn('term_id',$terms)->delete();
    Term::destroy($terms);
    $this->vocabulary->destroy($id);

    return response()->json(['OK']);
  }

  /**
   * Update a resource.
   *
   * @return Response
   */
  public function putUpdate(Request $request, $id) {
    $this->validate($request, isset($this->vocabulary->rules_create) ? $this->vocabulary->rules_create : $this->vocabulary->rules);

    $vocabulary = $this->vocabulary->findOrFail($id);
    $vocabulary->update(Input::only('name'));

    return Redirect::to(action('\Devfactory\Taxonomy\Controllers\TaxonomyController@getIndex'))->with('success', 'Updated');

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
    $content = json_decode(Input::get('json'));

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

  /**
   * Delete all the old TermRelations for orphaned content that has been deleted
   *
   *
   * @return
   */
  public function getPurgeDeadRelations() {
    $term_relations = TermRelation::get();

    foreach ($term_relations as $term_relation) {
      $model = $term_relation->relationable_type;
      $relation = $model::find($term_relation->relationable_id);

      if (is_null($relation)) {
        dump($term_relation->id);
        $term_relation->delete();
      }

      return Redirect::back();
    }
  }

}
