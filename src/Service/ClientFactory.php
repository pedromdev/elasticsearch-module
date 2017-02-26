<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\Client;
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
