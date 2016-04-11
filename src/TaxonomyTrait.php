<?php namespace Devfactory\Taxonomy;

use Devfactory\Taxonomy\Models\TermRelation;
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\Vocabulary;

trait TaxonomyTrait {

  /**
   * Get related vocabulary
   */

  public function getTaxonomiesAttribute()
  {
    $relatedGrouped = $this->related()->get()->groupBy('vocabulary_id');

    $groupedTaxonomy = [];

    foreach ($relatedGrouped as $key => $relateds) {

      $vocabulary = Vocabulary::find($key);

      $slug = str_slug($vocabulary->name,'_');

      $groupedTaxonomy[$slug] = [];
        // $groupedTaxonomy[$key]['name'] = $vocabulary->name;
        // $groupedTaxonomy[$key]['id'] = $vocabulary->id;

        // $groupedTaxonomy[$key]['terms'] = [];

      foreach ($relateds as $related) {

        $term = $related->term;

        $termData = [ 
        'id'                  => $term->id,
        'name'                => $term->name,
        'description'         => $term->description,
        'root_term_id'           => false,
        'root_term_name'         => false,
        'root_term_description'  => false
        ];

        $root = $term->root();

        if( $root->id != $term->id )
        {
          $termData['root_term_id'] = $root->id;
          $termData['root_term_name'] = $root->name;
          $termData['root_term_description'] = $root->description;
        }

        array_push( $groupedTaxonomy[$slug], $termData ); 
      }

    }

    return $this->attributes['taxonomies'] = $groupedTaxonomy;
  }
  /**
   * Return collection of tags related to the tagged model
   *
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function related() {
    return $this->morphMany('Devfactory\Taxonomy\Models\TermRelation', 'relationable');
  }

  /**
   * Set term to the inheriting model ( One to many )
   *
   * @param $term_id int
   *  The ID of the term or an instance of the Term object
   *
   * @return object
   *  The TermRelation object
   */
  public function setTerm($term, $description="") {

    $term = ($term instanceof Term) ? $term : Term::findOrFail($term);

    if(!$term)
      return;

    if( $this->hasTerm($term) )
      return $this->updateTerm($term,$description);

    // Check if the new term is already there
    // $related = $this->related()->first();

    // if( $related && $related->term->id == $term->id )
    // {
    //   return $this->updateTerm($term, $description);
    // }

    // Remove all term from same vocabulary
    $this->related()->where('relationable_id', $this->id)->where('vocabulary_id',$term->vocabulary->id)->delete();

    return $this->addTerm($term,$description);
  }

  /**
   * Add an existing term to the inheriting model (Many to Many )
   *
   * @param $term_id int
   *  The ID of the term or an instance of the Term object
   *
   * @return object
   *  The TermRelation object
   */
  public function addTerm($term, $description="") {

    $term = ($term instanceof Term) ? $term : Term::findOrFail($term);

    if(!$term)
      return;

    if( $this->hasTerm($term) )
      return $this->updateTerm($term,$description);

    $term_relation = [
    'term_id' => $term->id,
    'vocabulary_id' => $term->vocabulary_id,
    'description' => $description,
    ];

    return $this->related()->save(new TermRelation($term_relation));
  }



  /**
   * Update an existing term to the inheriting model
   *
   * @param $term_id int
   *  The ID of the term or an instance of the Term object   
   *
   * @return object
   *  The TermRelation object
   */
  public function updateTerm($term,$description="") {
    $term = ($term instanceof Term) ? $term : Term::findOrFail($term);

    if(!$term)
      return;

    if( !$this->related() )
      return;

    $this->related()->where('term_id',$term->id)->update(['description' => $description]);
  }


  /**
   * Check if the Model instance has the passed term as an existing relation
   *
   * @param mixed $term_id
   *  The ID of the term or an instance of the Term object
   *
   * @return object
   *  The TermRelation object
   */
  public function hasTerm($term) {

    $term = ($term instanceof Term) ? $term : Term::findOrFail($term);

    if(!$term)
      return false;

    return ($this->related()->where('term_id', $term->id )->count()) ? TRUE : FALSE;
  }

  /**
   * Get all the terms for a given vocabulary that are linked to the current
   * Model.
   *
   * @param $name string
   *  The name of the vocabulary
   *
   * @return object
   *  A collection of TermRelation objects
   */
  public function getTerms($vocabulary) {

    $vocabulary = \Devfactory\Taxonomy\Facades\TaxonomyFacade::getVocabulary($vocabulary);

    if(!$vocabulary)
      return;

    return  $this->related()->where('term_relations.vocabulary_id', $vocabulary->id)->get();
  }  

  /**
   * Get all the terms for a given vocabulary that are linked to the current
   * Model.
   *
   * @param $name string
   *  The name of the vocabulary
   *
   * @return object
   *  A collection of TermRelation objects
   */
  public function getTerm($vocabulary) {

    $vocabulary = \Devfactory\Taxonomy\Facades\TaxonomyFacade::getVocabulary($vocabulary);

    return  $this->related()->where('term_relations.vocabulary_id', $vocabulary->id)->first();
  }


  /**
   * Unlink the given term with the current model object
   *
   * @param $term_id int
   *  The ID of the term or an instance of the Term object
   *
   * @return bool
   *  TRUE if the term relation has been deleted, otherwise FALSE
   */
  public function removeTerm($term) {
    $term_id = ($term instanceof Term) ? $term->id : $term;

    return $this->related()->where('term_id', $term_id)->delete();
  }

  /**
   * Unlink all the terms from the current model object
   *
   * @return bool
   *  TRUE if the term relation has been deleted, otherwise FALSE
   */
  public function removeTerms($vocabulary=false) {

    $vocabulary = \Devfactory\Taxonomy\Facades\TaxonomyFacade::getVocabulary($vocabulary);

    if(!$vocabulary)
      return false;

    return $this->related()->where('vocabulary_id', $vocabulary->id )->delete();

  }

  /**
   * Filter the model to return a subset of entries matching the term ID
   *
   * @param object $query
   * @param int $term_id
   *
   * @return void
   */
  public function scopeWhereHasTerm($query, $term_id) 
  {
    if( is_int($term_id) )
    {
      return $query->whereHas('related', function($q) use($term_id) 
      {        
          $q->where('term_id', '=', $term_id );
      });
    } 

    if( method_exists($term_id,'toArray') )
      $term_id = $term_id->toArray();

    foreach ($term_id as $single_term_id) 
    {
      $query = $query->whereHas('related', function($q) use($single_term_id) 
      {
          $q->where('term_id', '=', $single_term_id );
      });
    }

    return $query;
  }  

  /**
   * Filter the model to return a subset of entries matching the term ID
   *
   * @param object $query
   * @param int $term_id
   *
   * @return void
   */
  public function scopeWhereHasVocabulary($query, $vocabulary_id) 
  {
    if( is_int($vocabulary_id) )
    {
      return $query->whereHas('related', function($q) use($vocabulary_id) 
      {        
          $q->where('vocabulary_id', '=', $vocabulary_id );
      });
    } 

    if( method_exists($vocabulary_id,'toArray') )
      $vocabulary_id = $vocabulary_id->toArray();

    foreach ($vocabulary_id as $single_vocabulary_id) 
    {
      $query = $query->whereHas('related', function($q) use($single_vocabulary_id) 
      {
          $q->where('vocabulary_id', '=', $single_vocabulary_id );
      });
    }

    return $query;
  }
}
