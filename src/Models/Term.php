<?php namespace Devfactory\Taxonomy\Models;

class Term extends \Eloquent {

  protected $hidden = ['created_at','updated_at'];

  protected $fillable = [
    'name',
    'vocabulary_id',
    'parent_id',
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
    return $this->hasMany('Devfactory\Taxonomy\Models\Term', 'parent_id', 'id');
  }

  public function parent() {
    return $this->hasOne('Devfactory\Taxonomy\Models\Term', 'id', 'parent_id');
  }

  public function root()
  {
    return \Devfactory\Taxonomy\Facades\TaxonomyFacade::recurseRoot($this);
  }


}
