<?php namespace Devfactory\Taxonomy\Composers;

class TaxonomyComposer {

    public function compose($view) {
      $prefix = rtrim(Config::get('taxonomy::route_prefix'), '.') . '.';

      $view->with('prefix', $prefix);
    }

}