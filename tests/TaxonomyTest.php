<?php

namespace Devfactory\Taxonomy\Test;

use Devfactory\Taxonomy\Taxonomy;
use \Mockery as m;

use Devfactory\Taxonomy\Models\Vocabulary;
use Devfactory\Taxonomy\Models\Term;
use Illuminate\Support\Facades\Facade;

class TaxonomyTest extends \PHPUnit_Framework_TestCase {

  protected $app;

  protected $taxonomy;

  protected $modelVocabulary;
  protected $modelTerm;

  protected $eloquent;

  /**
   * Clean mockery after each test
   *
   * @return
   */
  public function tearDown() {
    parent::tearDown();

    m::close();
  }

  public function setUp() {
    parent::setUp();

    // Setup app
    $this->app = m::mock('AppMock');
    $this->app->shouldReceive('instance')->andReturn($this->app);

    // Mock facades
    \Illuminate\Support\Facades\Facade::setFacadeApplication($this->app);

    // Instentiate class to test
    $this->eloquent = m::mock('Eloquent');

    $this->modelVocabulary = $this->mock('Devfactory\Taxonomy\Models\Vocabulary');
    $this->modelTerm = $this->mock('Devfactory\Taxonomy\Models\Term');

    $this->taxonomy = new Taxonomy($this->modelVocabulary, $this->modelTerm);
  }

  /**
   * Create a mock
   *
   * @param class
   * @param parentClass
   *
   * @return
   */
  public function mock($class, $parentClass = NULL)
  {
    if ($parentClass) {
      $mock = m::mock($parentClass, $class);
    } else {
      $mock = m::mock($class);
    }
    $this->app->instance($class, $mock);
    return $mock;
  }

  /**
   * Test the creation of a vocabulary term
   */
  public function testTaxonomyCreateVocabulary() {
    // Prepare data
    $name = 'MOCK_NAME';

    $data = [
      'name' => $name,
    ];

    // Mock
    $mock_count = m::mock('mockCount');
    $mock_count->shouldReceive('count')
      ->with()
      ->andReturn(FALSE);

    $this->modelVocabulary
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mock_count);

    // Mock
    $this->modelVocabulary
      ->shouldReceive('create')
      ->with($data)
      ->andReturn(TRUE);

    // Act
    $result = $this->taxonomy->createVocabulary($name);

    // Assert
    $this->assertTrue($result);
  }

  /**
   * Test the creation of an existing vocabulary name
   */
  public function testTaxonomyCreateVocabularyException() {
    // Prepare data
    $name = 'MOCK_NAME';

    // Mock
    $mock_count = m::mock('mockCount');
    $mock_count->shouldReceive('count')
      ->with()
      ->andReturn(TRUE);

    $this->modelVocabulary
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mock_count);

    $this->setExpectedException('\Devfactory\Taxonomy\Exceptions\VocabularyExistsException');

    // Act
    $result = $this->taxonomy->createVocabulary($name);
  }

  /**
   * Test the retrieval of a Vocabulary by ID
   */
  public function testTaxonomyGetVocabulary() {
    // Prepare data
    $id = 1;

    $this->modelVocabulary
      ->shouldReceive('find')
      ->with($id)
      ->andReturn(TRUE);

    // Act
    $result = $this->taxonomy->getVocabulary($id);

    // Assert
    $this->assertTrue($result);
  }

  /**
   * Test the retrieval of a Vocabulary by ID
   */
  public function testTaxonomyGetVocabularyByName() {
    // Prepare data
    $name = 'MOCK_NAME';

    // Mock
    $mock_first = m::mock('mockFirst');
    $mock_first->shouldReceive('first')
      ->with()
      ->andReturn(TRUE);

    $this->modelVocabulary
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mock_first);

    // Act
    $result = $this->taxonomy->getVocabularyByName($name);

    // Assert
    $this->assertTrue($result);
  }

  public function testGetVocabularyByNameAsArrayNotFound() {
    // Prepare data
    $name = 'MOCK_NAME';
    $result = null;

    // Mock
    $mockFirst = m::mock('first');
    $mockFirst->shouldReceive('first')
      ->andReturn($result);

    $this->modelVocabulary
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mockFirst);

    // Act
    $result = $this->taxonomy->getVocabularyByNameAsArray($name);

    // Assert
    $this->assertEmpty($result);
  }

  public function testGetVocabularyByNameAsArrayFound() {
    // Prepare data
    $name = 'MOCK_NAME';
    $vocabulary = new \StdClass();

    // Mock
    $mockToArray = m::mock('toArray');
    $mockToArray->shouldReceive('toArray')
      ->andReturn(true);

    $mockList = m::mock('pluck');
    $mockList->shouldReceive('pluck')
      ->with('name', 'id')
      ->andReturn($mockToArray);

    $vocabulary->terms = $mockList;
    $mockFirst = m::mock('first');
    $mockFirst->shouldReceive('first')
      ->andReturn($vocabulary);

    $this->modelVocabulary->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mockFirst);

    // Act
    $result = $this->taxonomy->getVocabularyByNameAsArray($name);

    // Assert
    $this->assertTrue($result);
  }

  /**
   * Test the creation of a vocabulary term
   */
  public function testTaxonomyDeleteVocabulary() {
    // Prepare data
    $id = 1;

    // Mock
    $mock_delete = m::mock('mockDelete');
    $mock_delete->shouldReceive('delete')
      ->with()
      ->andReturn(TRUE);

    $this->modelVocabulary
      ->shouldReceive('findOrFail')
      ->with($id)
      ->andReturn($mock_delete);

    // Act
    $result = $this->taxonomy->deleteVocabulary($id);

    // Assert
    $this->assertTrue($result);
  }

  /**
   * Test the creation of a vocabulary term
   */
  public function testTaxonomyDeleteVocabularyByName() {
    // Prepare data
    $name = 'MOCK_NAME';

    // Mock
    $mock_delete = m::mock('mockDelete');
    $mock_delete->shouldReceive('delete')
      ->with()
      ->andReturn(TRUE);

    $mock_first = m::mock('mockFirst');
    $mock_first->shouldReceive('first')
      ->with()
      ->andReturn($mock_delete);

    $this->modelVocabulary
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mock_first);

    // Act
    $result = $this->taxonomy->deleteVocabularyByName($name);

    // Assert
    $this->assertTrue($result);
  }

  /**
   * Test the creation of a vocabulary term
   */
  public function testTaxonomyDeleteVocabularyByNameFalse() {
    // Prepare data
    $name = 'MOCK_NAME';

    // Mock
    $mock_first = m::mock('mockFirst');
    $mock_first->shouldReceive('first')
      ->with()
      ->andReturn(NULL);

    $this->modelVocabulary
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mock_first);

    // Act
    $result = $this->taxonomy->deleteVocabularyByName($name);

    // Assert
    $this->assertFalse($result);
  }


  public function testTaxonomyCreateTerm() {
    // Prepare data
    $vid = 1;
    $name = 'MOCK_NAME';
    $parent = 0;
    $weight = 0;

    $term = [
      'name' => $name,
      'vocabulary_id' => $vid,
      'parent' => $parent,
      'weight' => $weight,
    ];

    // Mock
    $mock_create = $this->modelTerm->shouldReceive('create')
      ->with($term)
      ->andReturn(TRUE);

    $this->modelVocabulary
      ->shouldReceive('findOrFail')
      ->with($vid)
      ->andReturn($mock_create);

    // Act
    $result = $this->taxonomy->createTerm($vid, $name, $parent, $weight);

    // Assert
    $this->assertTrue($result);
  }

}
