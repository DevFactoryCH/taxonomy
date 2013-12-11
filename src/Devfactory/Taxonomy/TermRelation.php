<?php namespace Devfactory\Taxonomy;
use Illuminate\Database\Eloquent\Model as Eloquent;

class TermRelation extends Eloquent {
	protected $guarded = array();

	protected $table = 'term_relations';

	public static $rules = array();


	public function term() {

		return $this->belongsTo('term');
	}



	public static function getTerms($object, $object_id, $tolist = false, $byId = true) {

		if($tolist) {
			$list = array();
			$terms = TermRelation::where('object_id', $object_id)->where('object_type', $object)->get();

			if($byId) {
				foreach($terms as $t) {
					$list[$t->id] = $t->term->value;
				}
			}
			else {
				foreach($terms as $t) {
					$list[$t->term->value] = $t->term->value;
				}
			}
			return $list;
		}
		else {
			return TermRelation::where('object_id', $object_id)->where('object_type', $object_id)->get();
			
		}

	}





}
