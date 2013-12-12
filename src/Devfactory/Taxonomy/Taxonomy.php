<?php namespace Devfactory\Taxonomy;

class Taxonomy {

   /**
   * Link a entire vocabulary with an object
   * @param $object_type The name of the object
   * @param $object_id The id of the object
   * @param $voc_id The vocabulary id
   */
	public function setVocabulary($voc_id, $object_type, $object_id) {
		$terms = Term::where('vocabulary_id', $voc_id)->get();
		foreach($terms as $t) {
			$r = new TermRelation();
			$r->term_id = $t->id;
			$r->object_id = $object_id;
			$r->object_type = $object_type;
			$r->save();
		}

	}

	/**
   * Save a term in a specific vocabulary
   * @param $vid The id of the vocabulary
   * @param $term term's value
   * 
   * @return eloquent collection
   */
	public function termSave($vid, $term) {
		$vocabulary = Vocabulary::find($vid);
		if($vocabulary != null) {
			return $vocabulary->terms()->save(new Term(array('value' => $term)));
		}
		return false;
	}

	/**
   * Delete a term
   * @param $vid The id of the vocabulary
   * 
   * @return exception message failure
   */
	public function termDelete($tid) {
		try {
			Term::destroy($tid);
		}
		catch(\Exception $e) {
			return $e->getMessage();
		}	
	}

	 /**
   * Link a vocabulary with an object
   * @param $vid The id of the vocabulary
   * @param $toList get a list with ID/value
   * @param $withID get a list with value/value
   * 
   * @return Eloquent collections / array
   */
	public function getTerms($vid, $toList = false, $withID = true) {
		$terms = Term::where('vocabulary_id', $vid);
		if($toList) {
			if($withID) {
				$vocs = $terms->lists('value', 'id');
			}
			else {
				$vocs = $terms->lists('value', 'value');
			}
		}
		else {
			$vocs = $terms->get();
		}
		return $vocs;

	}

	public function tagObject($tid, $object_id, $object_type) {
		$termRelation = new TermRelation();
		$termRelation->term_id = $tid;
		$termRelation->object_id = $object_id;
		$termRelation->object_type = $object_type;
		$termRelation->save();
	}

	public function removeTag($tid, $object_id, $object_type) {
		$termRelation = TermRelation::where('term_id', $tid)
									->where('object_id', $object_id)
									->where('object_type', $object_type);
		if($termRelation != null ) {
			$termRelation->delete();
		}

	}

}