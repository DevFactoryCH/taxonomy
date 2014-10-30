<?php namespace Devfactory\Taxonomy\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Term extends Eloquent {

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
		return $this->belongsTo('Devfactory\Taxonomy\Vocabulary');
	}

}
