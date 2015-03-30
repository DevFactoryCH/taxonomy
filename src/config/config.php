<?php

return [
	'route_prefix' => 'admin',

	// Before filter
	'route_before' => [],

	// Enabled the use of the admin interace
	'route_enabled' => true,

  'layout' => [
    'extends' => 'layouts.admin',
    'header' => 'header',
    'content' => 'content',
  ],
];
