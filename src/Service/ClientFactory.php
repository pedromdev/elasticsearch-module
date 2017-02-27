<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\Client;
use Elasticsearch\Transport;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ClientFactory extends AbstractFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $transport = $serviceLocator->get($config['transport']);
        
        if (!$transport instanceof Transport) {
            throw new ServiceNotCreatedException(sprintf(
                "The transport must be an instance of %s, %s given",
                Transport::class,
                is_object($transport) ? get_class($transport) : gettype($transport)
            ));
        }
        $endpoint = $serviceLocator->get($config['endpoint']);
        return new Client($transport, $endpoint);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'client';
    }
}
