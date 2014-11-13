<?php namespace Devfactory\Taxonomy\Composers;

use Config;

class TaxonomyComposer {

    public function compose($view) {
      $prefix = rtrim(Config::get('taxonomy::route_prefix'), '.') . '.';

      $layout = (object) [
        'extends' => Config::get('taxonomy::config.layout.extends'),
        'header' => Config::get('taxonomy::config.layout.header'),
        'content' => Config::get('taxonomy::config.layout.content'),
      ];

      $view->with(['prefix' => $prefix, 'layout' => $layout]);
    }

}