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
    return $this->HasMany('Devfactory\Taxonomy\Term');
  }

  public function relations() {
    return $this->HasMany('Devfactory\Taxonomy\TermRelation');
  }

}
