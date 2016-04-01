<?php namespace Devfactory\Taxonomy;

use Devfactory\Taxonomy\Models\TermRelation;
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\Vocabulary;
use Devfactory\Taxonomy\Facades\TaxonomyFacade as Taxonomy;

trait TaxonomyTrait {

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

    // Check if the new term is already there
    $related = $this->related()->first();

    if( $related && $related->term->id == $term->id )
    {
      return $this->updateTerm($term, $description);
    }

    // Remove all term from same vocabulary
    $this->related()->where('relationable_id', $this->id)->delete();

    return $this->addTerm($term->id,$description);
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

    $vocabulary = Taxonomy::getVocabulary($vocabulary);

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

    $vocabulary = Taxonomy::getVocabulary($vocabulary);

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

    $vocabulary = Taxonomy::getVocabulary($vocabulary);

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
  public function scopeWhereHasTerm($query, $term_id) {
    return $query->whereHas('related', function($q) use($term_id) {

      if( method_exists($term_id,'toArray') )
        $term_id = $term_id->toArray();

      if (is_array($term_id)) {



        $q->whereIn('term_id', $term_id);
      }
      else {
        $q->where('term_id', '=', $term_id);
      }
    });
  }  

  /**
   * Filter the model to return a subset of entries matching the term ID
   *
   * @param object $query
   * @param int $term_id
   *
   * @return void
   */
  public function scopeWhereHasVocabulary($query, $vocabulary_id) {
    return $query->whereHas('related', function($q) use($vocabulary_id) {

      if( method_exists($vocabulary_id,'toArray') )
          $vocabulary_id = $vocabulary_id->toArray();

      if (is_array($vocabulary_id)) {

        $q->whereIn('vocabulary_id', $vocabulary_id);
      }
      else {
        $q->where('vocabulary_id', '=', $vocabulary_id);
      }
    });
  }
}
