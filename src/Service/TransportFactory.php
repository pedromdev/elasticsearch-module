<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\ConnectionPool\AbstractConnectionPool;
use Elasticsearch\Transport;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class TransportFactory extends AbstractFactory
{
 
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $retries = isset($config['retries']) ? $config['retries'] : 2;
        $sniffOnStart = isset($config['sniff_on_start']) ? $config['sniff_on_start'] : false;
        $connectionPool = $serviceLocator->get($config['connection_pool']);
        
        if (!$connectionPool instanceof AbstractConnectionPool) {
            throw new ServiceNotCreatedException(sprintf(
                "The connection pool must be an instance of %s, %s given",
                AbstractConnectionPool::class,
                is_object($connectionPool) ? get_class($connectionPool) : gettype($connectionPool)
            ));
        }
        $loggers = $serviceLocator->get($config['loggers']);
        
        return new Transport($retries, $sniffOnStart, $connectionPool, $loggers['logger']);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'transport';
    }
}
