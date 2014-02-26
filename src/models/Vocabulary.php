<?php namespace Devfactory\Taxonomy;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Vocabulary extends Eloquent {
  protected $guarded = array();

  protected $table = 'vocabularies';

  public static $rules = array(
    'value' => 'required');



  public function terms() {

    return $this->HasMany('Devfactory\Taxonomy\Term');
  }

  public function relations() {

    return $this->HasMany('Devfactory\Taxonomy\TermRelation');
  }

}
