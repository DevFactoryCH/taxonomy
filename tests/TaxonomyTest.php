<?php

use DevFactory\Taxonomy\Taxonomy;

class TaxonomyTest extends PHPUnit_Framework_TestCase {

  /**
   * Setup resources and dependencies.
   *
   * @return void
   */
  public function setUp() {
    // Setup app
    $this->app = m::mock('AppMock');
    $this->app->shouldReceive('instance')->andReturn($this->app);

    // Mock facades
    \Illuminate\Support\Facades\Facade::setFacadeApplication($this->app);
    \Illuminate\Support\Facades\Session::swap($this->session = m::mock('validatorMock'));
    \Illuminate\Support\Facades\Log::swap($this->log = m::mock('authMock'));
    \Illuminate\Support\Facades\Config::swap($this->config = m::mock('urlMock'));

    // Mock facades
    $this->config->shouldReceive('get')->with('mollom::dev', false)->andReturn(true);
    $this->config->shouldReceive('get')->with("mollom::mollom_public_key")->andReturn($this->publicKey);
    $this->config->shouldReceive('get')->with("mollom::mollom_private_key")->andReturn($this->privateKey);

    // Mock
    $this->client = new client(
      $this->guzzle     = m::mock('GuzzleHttp\Client')
    );
  }

  public function tearDown()  {
    m::close();
  }

  public function testTaxonomyCreateVocabulary()
  {
    $vocabulary = new Vocabulary();

    $this->assertTrue($nacho->hasCheese());
  }

}