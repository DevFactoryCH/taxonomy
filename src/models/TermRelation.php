<?php namespace Devfactory\Taxonomy;
use Illuminate\Database\Eloquent\Model as Eloquent;

class TermRelation extends Eloquent {
	protected $guarded = array();

	protected $table = 'term_relations';

	public static $rules = array();


	public function term() {

		return $this->belongsTo('Devfactory\Taxonomy\Term');
	}


	public function vocabulary() {

		return $this->belongsTo('Devfactory\Taxonomy\Vocabulary');
	}





}
