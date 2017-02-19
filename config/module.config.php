<?php

use Elasticsearch\ConnectionPool\StaticConnectionPool;
use Elasticsearch\Connections\ConnectionFactory;
use Psr\Log\NullLogger;

return [
    'elasticsearch' => [
        'connection' => [
            'default' => [
                'factory' => ConnectionFactory::class,
                'pool' => StaticConnectionPool::class,
            ],
        ],
        'loggers' => [
            'default' => [
                'logger' => NullLogger::class,
                'tracer' => NullLogger::class,
            ],
        ],
    ],
];