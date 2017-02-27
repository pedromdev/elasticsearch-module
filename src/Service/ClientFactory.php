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
    use InvalidTypeExceptionTrait;
    
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $transport = $serviceLocator->get($config['transport']);
        
        if (!$transport instanceof Transport) {
            throw $this->getException('transport', Transport::class, ServiceNotCreatedException::class, $transport);
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
