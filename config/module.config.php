<?php

use Elasticsearch\ConnectionPool\Selectors\RoundRobinSelector;
use Elasticsearch\ConnectionPool\StaticNoPingConnectionPool;
use Elasticsearch\Connections\ConnectionFactory;
use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModule\Service\Loggers\LoggerFactory;
use ElasticsearchModule\Service\Loggers\TracerFactory;
use Psr\Log\NullLogger;

return [
    'elasticsearch' => [
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
        'connection_pool' => [
            'default' => [
                'class' => StaticNoPingConnectionPool::class,
                'selector' => RoundRobinSelector::class,
                'connection_factory' => 'elasticsearch.connection_factory.default',
                'parameters' => [],
                'hosts' => [],
            ],
        ],
        'transport' => [
            'default' => [
                'retries' => 2,
                'sniff_on_start' => false,
                'connection_pool' => 'elasticsearch.connection_pool.default',
                'loggers' => 'elasticsearch.loggers.default',
            ],
        ],
        'endpoint' => [
            'default' => [
                'transport' => 'elasticsearch.transport.default',
                'serializer' => SmartSerializer::class,
            ],
        ],
    ],
];