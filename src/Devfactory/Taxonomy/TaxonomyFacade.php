<?php namespace Devfactory\Taxonomy;
 
use Illuminate\Support\Facades\Facade;
 
class TaxonomyFacade extends Facade {
 
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'taxonomy'; }
 
}