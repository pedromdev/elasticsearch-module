# ElasticsearchModule for Zend Framework 2

[![Build Status](https://travis-ci.org/pedromdev/elasticsearch-module.svg?branch=master)](https://travis-ci.org/pedromdev/elasticsearch-module) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pedromdev/elasticsearch-module/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pedromdev/elasticsearch-module/?branch=master) [![Coverage Status](https://coveralls.io/repos/github/pedromdev/elasticsearch-module/badge.svg?branch=dev)](https://coveralls.io/github/pedromdev/elasticsearch-module?branch=dev)

Integration between the Elasticsearch-PHP client and Zend Framework 2

## Installation

The installation is made through Composer. Add the dependency in your composer.json:

```json
{
    "require": {
        "pedromdev/elasticsearch-module": "~1.0"
    }
}
```
Then run command for installation:

```bash
php composer.phar install --no-dev
```

Or run update command if you already have an installation:

```bash
php composer.phar update --no-dev
```

After Composer install all dependencies, add ElasticsearchModule to your application.config.php:

```php
<?php

return [
    'modules' => [
        'ElasticsearchModule',
    ],
];
```

## ElasticsearchModule services and configurations

**Note:** These services and configurations are based on the DoctrineModule/DoctrineORMModule services and configurations.

### Services

#### Registered services
- `elasticsearch.loggers.default`: an `\ArrayObject` instance with `\Psr\Log\LoggerInterface` instances. Each instance is associated to a key in loggers configuration;
- `elasticsearch.handler.default`: default middleware callable;
- `elasticsearch.connection_factory.default`: an `\Elasticsearch\Connections\ConnectionFactoryInterface` instance;
- `elasticsearch.connection_pool.default`: an `\Elasticsearch\ConnectionPool\AbstractConnectionPool` instance;
- `elasticsearch.transport.default`: an `\Elasticsearch\Transport` instance;
- `elasticsearch.endpoint.default`: default callable to retrieve an `\Elasticsearch\Endpoints\AbstractEndpoint` instances. Its only parameter is an endpoint class name (e.g., Get, Bulk, Delete);
- `elasticsearch.client.default`: an `\Elasticsearch\Client` instance;
- `Elasticsearch\Client`: an alias of `elasticsearch.client.default`.

#### Usage

```php
<?php

$client = $serviceLocator->get('elasticsearch.client.default');
$client = $serviceLocator->get('Elasticsearch\Client');
```

### Configuration

Create a config/autoload/elasticsearch.global.php file with the below content:

```php
<?php

reuturn [
    'connection_pool' => [
        'default' => [
            'hosts' => [
                'http://localhost:9200', // string based
                'http://username:password@localhost:9200', // if you have an authentication layer
                [
                    'scheme' => 'http', // associative array based
                    'host' => 'localhost',
                    'port' => 9200,
                    'user' => 'username', // if you have an authentication layer
                    'pass' => 'password',
                ],
            ],
        ],
    ],
];
