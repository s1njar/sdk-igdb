<?php

use Jschubert\Igdb\Builder\SearchBuilder;

require_once __DIR__ . '/vendor/autoload.php';

$apiKey = '8293596464461cd84edf92dec0363a1e';

$searchBuilder = new SearchBuilder($apiKey);

$response = $searchBuilder
    ->addEndpoint('games')
    ->addFields(['id'])
    ->addFilter('rating', 'eq', '75')
    ->search()
    ->get();

var_dump($response);