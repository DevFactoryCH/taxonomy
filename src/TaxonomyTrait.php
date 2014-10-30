<?php namespace DevFactory\Taxonomy;

use DevFactory\Taxonomy\Models\TermRelation;
use DevFactory\Taxonomy\Models\Term;

trait TaxonomyTrait {

  /**
	 * Return collection of tags related to the tagged model
	 *
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function related() {
		return $this->morphMany('DevFactory\Taxonomy\Models\TermRelation', 'relationable');
	}

  public function addTerm($term_id) {
    $term = Term::findOrFail($term_id);

    $term_relation = [
      'term_id' => $term->id,
      'vocabulary_id' => $term->vocabulary_id,
    ];

    $this->related()->save(new TermRelation($term_relation));
  }

  public function getTermsByVocabularyName($name) {
    $vocabulary = \Taxonomy::getVocabularyByName($name);

    return $this->related()->where('vocabulary_id', $vocabulary->id)->get();
  }

  public function getTermsByVocabularyNameAsArray($name) {
    $vocabulary = \Taxonomy::getVocabularyByName($name);

    $term_relations = $this->related()->where('vocabulary_id', $vocabulary->id)->get();

    $data = [];
    foreach ($term_relations as $term_relation) {
      $data[$term_relation->term->id] = $term_relation->term->name;
    }

    return $data;
  }

  public function removeTerm($term_id) {
    $this->related()->where('term_id', $term_id)->delete();
  }

  public function removeAllTerms() {
    $this->related()->delete();
  }

}