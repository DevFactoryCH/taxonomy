<?php namespace Devfactory\Taxonomy;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Term extends Eloquent {

	protected $guarded = array();

	public static $rules = array(
		'value' => 'required');
}
