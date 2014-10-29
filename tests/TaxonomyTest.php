<?php

namespace DevFactory\Taxonomy\Test;

use DevFactory\Taxonomy\Taxonomy;
use \Mockery as m;

use DevFactory\Taxonomy\Models\Vocabulary;
use Illuminate\Support\Facades\Facade;

class TaxonomyTest extends \PHPUnit_Framework_TestCase {

  protected $taxonomy;
  protected $app;
  protected $vocabularyModel;
  protected $eloquent;

  /**
   * Clean mockery after each test
   *
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
    $this->vocabularyModel = $this->mock('DevFactory\Taxonomy\Models\Vocabulary');
    $this->taxonomy = new Taxonomy($this->vocabularyModel);
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

    $this->vocabularyModel
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mock_count);

    // Mock
    $this->vocabularyModel
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

    $this->vocabularyModel
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn($mock_count);

    $this->setExpectedException('\DevFactory\Taxonomy\Exceptions\VocabularyExistsException');

    // Act
    $result = $this->taxonomy->createVocabulary($name);
  }

  /**
   * Test the retrieval of a Vocabulary by ID
   */
  public function testTaxonomyGetVocabulary() {
    // Prepare data
    $id = 1;

    $this->vocabularyModel
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

    $this->vocabularyModel
      ->shouldReceive('where')
      ->with('name', $name)
      ->andReturn(TRUE);

    // Act
    $result = $this->taxonomy->getVocabularyByName($name);

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

    $this->vocabularyModel
      ->shouldReceive('findOrFail')
      ->with($id)
      ->andReturn($mock_delete);

    // Act
    $result = $this->taxonomy->deleteVocabulary($id);

    // Assert
    $this->assertTrue($result);
  }

}