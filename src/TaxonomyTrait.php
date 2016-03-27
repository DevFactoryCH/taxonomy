<?php namespace Devfactory\Taxonomy;

use Devfactory\Taxonomy\Models\TermRelation;
use Devfactory\Taxonomy\Models\Term;

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
   * Add an existing term to the inheriting model (Many to Many )
   *
   * @param $term_id int
   *  The ID of the term or an instance of the Term object
   *
   * @return object
   *  The TermRelation object
   */
  public function addTerm($term_id,$description="") {
    
    $term = ($term_id instanceof Term) ? $term_id : Term::findOrFail($term_id);

    if(!$term)
      return;

    $term_relation = [
      'term_id' => $term->id,
      'vocabulary_id' => $term->vocabulary_id,
      'description' => $description,
    ];

    $this->related()->save(new TermRelation($term_relation));
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
  public function setTerm($term_id,$description="") {

    $term = ($term_id instanceof Term) ? $term_id : Term::findOrFail($term_id);

    if(!$term)
      return;

    // Remove all term from same vocabulary
    $this->related()->where('vocabulary_id',$term->vocabulary_id)->delete();

    $this->addTerm($term_id,$description);
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
  public function updateTerm($term_id,$description="") {
    $term = ($term_id instanceof Term) ? $term_id : Term::findOrFail($term_id);

    if(!$term)
      return;

    if( !$this->related() )
      return;

    $this->related()->where('term_id',$term_id)->update(['description' => $description]);
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
  public function hasTerm($term_id) {
    $term = ($term_id instanceof Term) ? $term_id : Term::findOrFail($term_id);

    $term_relation = [
      'term_id' => $term->id,
      'vocabulary_id' => $term->vocabulary_id,
    ];

    return ($this->related()->where('term_id', $term_id)->count()) ? TRUE : FALSE;
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
  public function getTermsByVocabularyName($name) {
    $vocabulary = \Taxonomy::getVocabularyByName($name);

    if(!$vocabulary)
      return [];

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
  public function getTermByVocabularyName($name) {

    $vocabulary = \Taxonomy::getVocabularyByName($name);

    if(!$vocabulary)
      return [];

    return $this->related()->where('vocabulary_id', $vocabulary->id)->first();

  }

  /**
   * Get all the terms for a given vocabulary that are linked to the current
   * Model as a key value pair array.
   *
   * @param $name string
   *  The name of the vocabulary
   *
   * @return array
   *  A key value pair array of the type 'id' => 'name'
   */
  public function getTermsByVocabularyNameAsArray($name) {
    $vocabulary = \Taxonomy::getVocabularyByName($name);

    $term_relations = $this->related()->where('vocabulary_id', $vocabulary->id)->get();

    $data = [];
    foreach ($term_relations as $term_relation) {
      $data[$term_relation->term->id] = $term_relation->term->name;
    }

    return $data;
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
  public function removeTerm($term_id) {
    $term_id = ($term_id instanceof Term) ? $term_id->id : $term_id;
    return $this->related()->where('term_id', $term_id)->delete();
  }

  /**
   * Unlink all the terms from the current model object
   *
   * @return bool
   *  TRUE if the term relation has been deleted, otherwise FALSE
   */
  public function removeAllTerms($vocabulary_name=false) {


    if($vocabulary_name)
    {
      $vocabulary = \Taxonomy::getVocabularyByName($name);
      
      return $this->related()->where('vocabulary_id',$vocabulary->id)->delete();
    }

    return $this->related()->delete();
  }

  /**
   * Filter the model to return a subset of entries matching the term ID
   *
   * @param object $query
   * @param int $term_id
   *
   * @return void
   */
  public function scopeGetAllByTermId($query, $term_id) {
    return $query->whereHas('related', function($q) use($term_id) {
      if (is_array($term_id)) {
        $q->whereIn('term_id', $term_id);
      }
      else {
        $q->where('term_id', '=', $term_id);
      }
    });
  }
}
