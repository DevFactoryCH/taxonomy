<?php namespace DevFactory\Taxonomy;

use DevFactory\Taxonomy\Models\Vocabulary;

class Taxonomy {

  protected $vocabulary;
  protected $term;
  protected $term_relation;

  public function __construct(Vocabulary $vocabulary, Term $term) {
    $this->vocabulary = $vocabulary;
    $this->term = $term;
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
    $vocabulary = $this->vocabulary->findOrFail($id);

    return $vocabulary->delete();
  }

  /**
   * Create a new term in a specific vocabulary
   *
   * @param int $vid
   *  The Vocabulary ID in which to add the term
   *
   * @param string $name
   *  The name of the term
   *
   * @param int $parent
   *  The ID of the parent term if it is a child
   *
   * @param int $weight
   *  The weight of the term in order to sort them inside the Vocabulary
   *
   * @return int
   *  The ID of the created term
   *
   * @thrown Illuminate\Database\Eloquent\ModelNotFoundException
   */
  public function createTerm($vid, $name, $parent = 0, $weight = 0) {
    if ($vocabulary = $this->vocabulary->findOrFail($id)) {
      $term = [
        'name' => $name,
        'vocabulary_id' => $vid,
        'parent' => $parent,
        'weight' => $weight,
      ];

      return $this->term->create($term);
    }
  }
}