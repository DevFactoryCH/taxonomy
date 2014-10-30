<?php namespace DevFactory\Taxonomy\Models;

class Vocabulary extends \Eloquent {

  protected $fillable = [
    'name',
  ];

  protected $table = 'vocabularies';

  public static $rules = [
    'value' => 'required'
  ];

  public function terms() {
    return $this->HasMany('DevFactory\Taxonomy\Models\Term');
  }

  public function relations() {
    return $this->HasMany('TermRelation');
  }

}
