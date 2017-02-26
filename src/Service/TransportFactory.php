<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\Transport;
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
