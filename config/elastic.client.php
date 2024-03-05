<?php

declare(strict_types=1);
$configuration = [
    'hosts' => [
        env('ELASTIC_HOST', 'localhost:9200'),
    ],
];

if(env('ELASTIC_PASSWORD') && env('ELASTIC_USERNAME'))
    $configuration['basicAuthentication'] = [
        env('ELASTIC_USERNAME'),
        env('ELASTIC_PASSWORD'),
    ];

return $configuration;
