<?php namespace Devfactory\Taxonomy;

use Devfactory\Taxonomy\Models\Vocabulary;
use Devfactory\Taxonomy\Models\Term;


class Taxonomy {

  protected $vocabulary;
  protected $term;
  protected $term_relation;

  public function __construct(Vocabulary $vocabulary, Term $term) 
  {
    // Inject required Models
    $this->vocabulary = $vocabulary;
    $this->term = $term;
  }

  /**
   * Create a new Vocabulary with the given name
   *
   * @param string $name
   *  The name of the Vocabulary
   *
   * @return mixed
   *  The Vocabulary object if created, FALSE if error creating,
   *  Exception if the vocabulary name already exists.
   */
  public function createVocabulary($name) 
  {
    if ($this->vocabulary->where('name', $name)->count()) 
    {
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
  public function getVocabulary($id) 
  {
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
  public function getVocabularyByName($name) 
  {
    return $this->vocabulary->where('name', $name)->first();
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
  public function getTerm( $vocabulary, $name ) 
  {

    if( $vocabulary instanceof \Devfactory\Taxonomy\Models\Vocabulary )
      $vocabulary_id = $vocabulary->id;

    if( is_string( $vocabulary ) )
    {

      $vocabulary = $this->getVocabularyByName( $vocabulary );

      if( !$vocabulary )
        return false;
    
      $vocabulary_id = $vocabulary->id;

    }


    return $this->term->where( 'vocabulary_id', $vocabulary_id )->where( 'name', $name )->first();
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
  public function getTermsByNameAsArray( $vocabulary_name , $field='name' ) 
  {
    $vocabulary = $this->vocabulary->where('name', $vocabulary_name)->first();

    if (!is_null($vocabulary)) {
      return $vocabulary->terms->lists('name', 'id')->toArray();
    }

    return [];
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
  public function getTerms($vocabulary, $parentName = true, $withParent = false) 
  {
    if( $vocabulary instanceof \Devfactory\Taxonomy\Models\Vocabulary )
      $vocabulary = $vocabulary;
    elseif( is_string( $vocabulary ) )
      $vocabulary = $this->getVocabularyByName( $vocabulary );
    else 
      return collect([]);

    if( $parentName === true )
      return $vocabulary->terms;

    if( is_string($parentName) )
    {
      $term = $this->getTerm($vocabulary, $parentName);

      $terms = $vocabulary->terms()->where($this->term->getTable().'.parent_id', $term->id)->get();

      if( $withParent )
        return $terms->push($term);

      return $terms;
    }

    return collect([]);
  }

  /**
   * Get a Vocabulary by name as an options array for dropdowns
   *
   * @param string $name
   *  The name of the Vocabulary to fetch
   *
   * @return
   *  The Vocabulary Model object, otherwise NULL
   */
  public function getVocabularyByNameOptionsArray($name) 
  {
    $vocabulary = $this->vocabulary->where('name', $name)->first();

    if (is_null($vocabulary)) {
      return collect([]);
    }

    $parents = $this->term->whereParent(0)
      ->whereVocabularyId($vocabulary->id)
      ->orderBy('weight', 'ASC')
      ->get();

    $options = [];
    foreach ($parents as $parent) {
      $options[$parent->id] = $parent->name;
      $this->recurse_children($parent, $options);
    }

    return $options;
  }

  /**
   * Recursively visit the children of a term and generate the '- ' option array for dropdowns
   *
   * @param Object $parent
   * @param array  $options
   * @param int    $depth
   *
   * @return array
   */
  private function recurse_children($parent, &$options, $depth = 1) 
  {
    $parent->childrens->map(function($child) use (&$options, $depth) {
      $options[$child->id] = str_repeat('-', $depth) .' '. $child->name;

      if ($child->childrens) {
        $this->recurse_children($child, $options, $depth+1);
      }
    });
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
  public function deleteVocabulary($vocabulary) {

    if( is_string( $vocabulary ) )
    {

      $vocabulary = $this->getVocabularyByName( $vocabulary );

      if( !$vocabulary )
        return false;
    
      return $vocabulary->delete();
    }

    return false;
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
  public function createTerm($vocabulary_name, $term = array() ) 
  {
    $vocabulary = $this->vocabulary->where('name', $vocabulary_name)->first();

    if(!$vocabulary)
    {
      throw new Exceptions\VocabularyNotExistsException();
    }


    // if($this->vocabulary->terms)

    $term['vocabulary_id'] = $vocabulary->id;
    $term['parent_id'] = isset($term['parent_id']) ? $term['parent_id'] : 0  ;
    $term['weight'] = isset($term['weight']) ? $term['weight'] : 0  ;

    if($vocabulary->terms()
        ->where($this->term->getTable().'.parent_id', $term['parent_id'])
        ->where($this->term->getTable().'.name', $term['name'])
        ->first())
    {
      throw new Exceptions\TermExistsException();
    }

    return $this->term->create($term);
  }

}