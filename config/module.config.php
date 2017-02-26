<?php

use Elasticsearch\Client;
use Elasticsearch\ConnectionPool\Selectors\RoundRobinSelector;
use Elasticsearch\ConnectionPool\StaticNoPingConnectionPool;
use Elasticsearch\Connections\ConnectionFactory as ElasticsearchConnectionFactory;
use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModule\Service\ClientFactory;
use ElasticsearchModule\Service\ConnectionFactory;
use ElasticsearchModule\Service\ConnectionPoolFactory;
use ElasticsearchModule\Service\EndpointFactory;
use ElasticsearchModule\Service\HandlerFactory;
use ElasticsearchModule\Service\Loggers\LoggerFactory;
use ElasticsearchModule\Service\Loggers\TracerFactory;
use ElasticsearchModule\Service\LoggersFactory;
use ElasticsearchModule\Service\TransportFactory;
use ElasticsearchModule\ServiceFactory\AbstractElasticsearchServiceFactory;
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
                'factory' => ElasticsearchConnectionFactory::class,
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
        'client' => [
            'default' => [
                'transport' => 'elasticsearch.transport.default',
                'endpoint' => 'elasticsearch.endpoint.default',
            ],
        ],
    ],
    'elasticsearch_factories' => [
        'loggers' => LoggersFactory::class,
        'handler' => HandlerFactory::class,
        'connection_factory' => ConnectionFactory::class,
        'connection_pool' => ConnectionPoolFactory::class,
        'transport' => TransportFactory::class,
        'endpoint' => EndpointFactory::class,
        'client' => ClientFactory::class,
    ],
    'service_manager' => [
        'abstract_factories' => [
            AbstractElasticsearchServiceFactory::class,
        ],
        'aliases' => [
            Client::class => 'elasticsearch.client.default',
        ],
    ],
];