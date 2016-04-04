<?php namespace Devfactory\Taxonomy\Models;

class TermRelation extends \Eloquent {
  protected $hidden = ['created_at','updated_at'];
  
  protected $fillable = [
    'term_id',
    'vocabulary_id',
    'description'
  ];

  protected $table = 'term_relations';

  public function relationable() {
    return $this->morphTo();
  }

    public function vocabulary() {
    return $this->belongsTo('Devfactory\Taxonomy\Models\Vocabulary');
  }


  public function term() {
    return $this->belongsTo('Devfactory\Taxonomy\Models\Term');
  }

}
