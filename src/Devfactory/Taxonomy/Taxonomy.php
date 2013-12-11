<?php namespace Devfactory\Taxonomy;

class Taxonomy {


	public static function lol() {
		return 'lol';
	}

	public function getVocabularyByName($id) {

		//
	}
	 /**
   * Link a vocabulary with an object
   * @param $voc_id The id of the vocabulary
   * @param $withID get a list with ID/value
   * 
   * @return array
   */
	public function getVocabularyToList($voc_id, $withID = true) {

		if($withID) {
			$vocs = Term::where('vocabulary_id', $voc_id)->lists('value', 'id');
		}
		else {
			$vocs = Term::where('vocabulary_id', $voc_id)->lists('value', 'value');
		}
		return $vocs;
	}


   /**
   * Link a vocabulary with an object
   * @param $object_type The name of the object
   * @param $object_id The id of the object
   * @param $voc_id The vocabulary id
   */
	public function linkVocabulary($voc_id, $object_type, $object_id) {
		$terms = Term::where('vocabulary_id', $voc_id)->get();
		foreach($terms as $t) {
			$r = new TermRelation();
			$r->term_id = $t->id;
			$r->object_id = $object_id;
			$r->object_type = $object_type;
			$r->save();
		}

	}




}