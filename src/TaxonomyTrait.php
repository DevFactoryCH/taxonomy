<?php namespace DevFactory\Taxonomy;

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
    $term_relation = [
      'term_id' => $term_id,
    ];

    $this->related()->save($term_relation);
  }

  public function removeTerm($term_id) {
    $this->related()->where('term_id', $term_id)->delete();
  }

}