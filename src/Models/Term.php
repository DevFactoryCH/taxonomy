<?php namespace Devfactory\Taxonomy\Models;

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
    return $this->morphMany('Devfactory\Taxonomy\Models\TermRelation', 'relationable');
  }

	public function vocabulary() {
		return $this->belongsTo('Devfactory\Taxonomy\Models\Vocabulary');
	}

}
