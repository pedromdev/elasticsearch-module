<?php

use Elasticsearch\ConnectionPool\StaticConnectionPool;
use Elasticsearch\Connections\ConnectionFactory;
use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModule\Service\Loggers\LoggerFactory;
use ElasticsearchModule\Service\Loggers\TracerFactory;
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
                'factories' => [
                    'logger' => LoggerFactory::class,
                    'tracer' => TracerFactory::class,
                ],
                'logger' => NullLogger::class,
                'tracer' => NullLogger::class,
            ],
        ],
        'handler' => [
            'default' => [
                'params' => [
                    'multi_handler' => [],
                    'single_handler' => [],
                ],
            ],
        ],
        'connection_factory' => [
            'default' => [
                'factory' => ConnectionFactory::class,
                'handler' => 'elasticsearch.handler.default',
                'params' => [],
                'serializer' => SmartSerializer::class,
                'loggers' => 'elasticsearch.loggers.default',
            ],
        ],
    ],
];