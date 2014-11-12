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
   *  The ID of the term to link
   *
   * @return object
   *  The TermRelation object
   */
  public function addTerm($term_id) {
    $term = Term::findOrFail($term_id);

    $term_relation = [
      'term_id' => $term->id,
      'vocabulary_id' => $term->vocabulary_id,
    ];

    $this->related()->save(new TermRelation($term_relation));
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
   *  The ID of the term
   *
   * @return bool
   *  TRUE if the term relation has been deleted, otherwise FALSE
   */
  public function removeTerm($term_id) {
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

}