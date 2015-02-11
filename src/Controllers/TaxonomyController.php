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

    $terms = $vocabulary->terms()->orderBy('parent', 'ASC')->orderBy('weight', 'ASC')->get();

    $terms = $this->parseTree($terms);

    return View::make('taxonomy::vocabulary.edit', compact('vocabulary', 'terms'));
  }

  /**
   * Recursive method to build the heirarchy tree of terms for the nestable
   * component in the backoffice.
   *
   * @param mixed $tree
   * @param int   $parent
   *
   * @return array
   */
  private function parseTree($tree, $parent = 0) {
    $return = array();

    // Traverse the tree and search for direct children of the root
    foreach($tree as $key => $term) {
      // A direct child is found
      if ($term->parent == $parent) {
        // Remove item from tree (we don't need to traverse this again)
        unset($tree[$key]);
        // Append the child into result array and parse its children
        $return[] = array(
          'term' => $term,
          'children' => $this->parseTree($tree, $term->id)
        );
      }
    }
    return empty($return) ? null : $return;
  }

  /**
   * Helper function to update the terms parent/weight values after
   * being moved in the admin interface
   *
   * @param int $id
   *
   * @return void
   */
  public function orderTerms($id) {
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

        if (empty($child->children)) {
          continue;
        }

        foreach ($child->children as $grand_child_key => $grand_child){
          $grand_child = Term::find($grand_child->id);

          $grand_child->parent = $child_term->id;
          $grand_child->weight = $grand_child_key;

          $grand_child->save();
        }
      }
    }

  }

}
