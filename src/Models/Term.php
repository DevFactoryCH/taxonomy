<?php namespace DevFactory\Taxonomy\Models;

class Term extends \Eloquent {

  protected $fillable = [
    'name',
    'vocabulary_id',
    'parent',
    'weight',
  ];

	public static $rules = [
		'name' => 'required'
  ];

  public function termRelation() {
    return $this->morphMany('TermRelation', 'relationable');
  }

	public function vocabulary() {
		return $this->belongsTo('Vocabulary');
	}

}
