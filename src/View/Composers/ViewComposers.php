<?php

namespace Devfactory\Taxonomy\View\Composers;

class TaxonomyComposer
{
    public function compose($view)
    {
        $prefix = rtrim(config('taxonomy.route_prefix'), '.') . '.';

        $layout = (object) [
            'extends' => config('taxonomy.config.layout.extends'),
            'header' => config('taxonomy.config.layout.header'),
            'content' => config('taxonomy.config.layout.content'),
        ];

        $view->with(['prefix' => $prefix, 'layout' => $layout]);
    }
}
