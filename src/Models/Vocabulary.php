<?php namespace Devfactory\Taxonomy\Models;

class Vocabulary extends \Eloquent {

  protected $fillable = [
    'name',
  ];

  protected $table = 'vocabularies';

  public static $rules = [
    'name' => 'required'
  ];

  public function terms() {
    return $this->HasMany('Devfactory\Taxonomy\Models\Term');
  }

  public function relations() {
    return $this->HasMany('Devfactory\Taxonomy\Models\TermRelation');
  }

}
