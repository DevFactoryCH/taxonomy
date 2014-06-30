<?php namespace Devfactory\Taxonomy;

class Taxonomy {

	public function createVocabulary($vocName, $vid = null) {
		$voc = new Vocabulary();
		$voc->value = $vocName;
		if($vid != null) {
			$voc->id = $vid;
		}
		$voc->save();
	}

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
			$termValid = $vocabulary->terms()->where('value', $term)->first();
			if($termValid != null) {
				return $termValid;
			}
			else {
				return $vocabulary->terms()->save(new Term(array('value' => $term)));
			}
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

	public function getTermsByVocabularyName($name, $toList = false, $withID = true) {
		$voc = Vocabulary::where('value', $name);
		if($voc != null) {
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
		}
		return $vocs;

	}

	public function tagObject($tid, $vid, $object_id, $object_type) {
		if(TermRelation::where('term_id', $tid)->where('object_id', $object_id)->where('object_type', $object_type)->where('vocabulary_id', $vid)->count() < 1) {
			$termRelation = new TermRelation();
			$termRelation->term_id = $tid;
			$termRelation->vocabulary_id = $vid;
			$termRelation->object_id = $object_id;
			$termRelation->object_type = $object_type;
			$termRelation->save();

			return $termRelation;
		}
	}

	//detach a tags from an object by term_id
	public function removeTag($tid, $object_id, $object_type) {
		$termRelation = TermRelation::where('term_id', $tid)
									->where('object_id', $object_id)
									->where('object_type', $object_type);
		if($termRelation != null ) {
			$termRelation->delete();
		}

	}

	//detach a tags from an object by vid
	public function detachTags($vid, $object_id, $object_type) {
		return TermRelation::where('object_id', $object_id)
							->where('object_type', $object_type)
							->where('vocabulary_id', $vid)
							->delete();
	}



	public function getTermsRelation($vid, $object_id, $object_type) {

		return TermRelation::where('vocabulary_id', $vid)
									->where('object_id', $object_id)
									->where('object_type', $object_type)
									->get();
	}

	//remove and delete relation of a term
	public function removeTerm($vid, $term_id) {
		Term::destroy($term_id);
		TermRelation::where('vocabulary_id', $vid)
									->where('term_id', $term_id)
									->delete();
	}

	public function removeVocabulary($vid) {
		$voc = Vocabulary::find($vid);
		if($voc != null) {
			$voc->terms()->delete();
			$voc->relations()->delete();
			$voc->delete();

		}

	}

	public function updateTerm($id, $value) {
		$term = Term::find($id);
		if($term != null && $value != "" ) {
			$term->value = $value;
			$term->save();
		}
	}

	public function getTerm($id) {
		return Term::find($id);
	}

}