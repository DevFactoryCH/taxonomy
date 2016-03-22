<?php namespace Devfactory\Taxonomy\Models;

class Term extends \Eloquent {

  protected $fillable = [
    'name',
    'vocabulary_id',
    'parent',
    'weight',
    'description'
  ];

  public static $rules = [
    'name' => 'required'
  ];

  public function termRelation() {
    return $this->morphMany('TermRelation', 'relationable');
  }

  public function vocabulary() {
    return $this->belongsTo('Devfactory\Taxonomy\Models\Vocabulary');
  }

  public function childrens() {
    return $this->hasMany('Devfactory\Taxonomy\Models\Term', 'parent', 'id')
      ->orderBy('weight', 'ASC');
  }

  public function parentTerm() {
    return $this->hasOne('Devfactory\Taxonomy\Models\Term', 'id', 'parent');
  }
}
