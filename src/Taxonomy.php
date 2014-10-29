<?php namespace DevFactory\Taxonomy;

use DevFactory\Taxonomy\Models\Vocabulary;

class Taxonomy {

  protected $vocabulary;

  public function __construct(Vocabulary $vocabulary) {
    $this->vocabulary = $vocabulary;
  }

  /**
   * Create a new Vocabulary with the given name
   *
   * @param string $name
   *  The name of the Vocabulary
   *
   * @return bool
   *  TRUE if vocabulary created, otherwise FALSE
   */
  public function createVocabulary($name) {
    if ($this->vocabulary->where('name', $name)->count()) {
      throw new Exceptions\VocabularyExistsException();
    }

		return $this->vocabulary->create(['name' => $name]);
	}

  /**
   * Get a Vocabulary by ID
   *
   * @param int $id
   *  The id of the Vocabulary to fetch
   *
   * @return
   *  The Vocabulary Model object, otherwise NULL
   */
  public function getVocabulary($id) {
    return $this->vocabulary->find($id);
  }

  /**
   * Get a Vocabulary by name
   *
   * @param string $name
   *  The name of the Vocabulary to fetch
   *
   * @return
   *  The Vocabulary Model object, otherwise NULL
   */
  public function getVocabularyByName($name) {
    return $this->vocabulary->where('name', $name);
  }

  /**
   * Delete a Vocabulary by ID
   *
   * @param int $id
   *  The ID of the Vocabulary to delete
   *
   * @return bool
   *  TRUE if Vocabulary is deletes, otherwise FALSE
   *
   * @thrown Illuminate\Database\Eloquent\ModelNotFoundException
   */
  public function deleteVocabulary($id) {
    // Find the Vocabulary using the vocabulary id
    $vocabulary = $this->vocabulary->findOrFail($id);
    // Delete the Vocabulary
    return $vocabulary->delete();
  }

}