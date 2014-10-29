<?php namespace DevFactory\Taxonomy;

class Taxonomy {

  public function createVocabulary($name, $vid = null) {
		Vocabulary::create(['name' => $name]);

    $this->vocabulary->name = $name;

		if (!is_null($vid)) {
			$this->vocabulary->id = $vid;
		}

		$this->vocabulary->save();
	}

}