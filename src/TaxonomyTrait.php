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
   * Add an existing term to the inheriting model
   *
   * @param $term_id int
   *  The ID of the term or an instance of the Term object
   *
   * @return object
   *  The TermRelation object
   */
  public function addTerm($term_id) {
    $term = ($term_id instanceof Term) ? $term_id : Term::findOrFail($term_id);

    $term_relation = [
      'term_id' => $term->id,
      'vocabulary_id' => $term->vocabulary_id,
    ];

    return $this->related()->save(new TermRelation($term_relation));
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

    return $this->related()->where('vocabulary_id', $vocabulary->id)->get();
  }

  /**
   * Get all the terms for a given vocabulary that are linked to the current
   * Model.
   *
   * @param $slug string
   *  The name of the vocabulary
   *
   * @return object
   *  A collection of TermRelation objects
   */
  public function getTermsByVocabularySlug($slug) {
    $vocabulary = \Taxonomy::getVocabularyBySlug($slug);

    return $this->related()->where('vocabulary_id', $vocabulary->id)->get();
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
   * Get all the terms for a given vocabulary that are linked to the current
   * Model as a key value pair array.
   *
   * @param $slug string
   *  The name of the vocabulary
   *
   * @return array
   *  A key value pair array of the type 'id' => 'name'
   */
  public function getTermsByVocabularySlugAsArray($slug) {
    $vocabulary = \Taxonomy::getVocabularyBySlug($slug);

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
  public function removeAllTerms() {
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
