<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\Client;
use Elasticsearch\Transport;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ClientFactory extends AbstractFactory
{
    use InvalidTypeExceptionTrait;
    
    /**
     * {@inheritDoc}
     */
    protected function create(ContainerInterface $container, $config)
    {
        $transport = $container->get($config['transport']);
        
        if (!$transport instanceof Transport) {
            throw $this->getException('transport', Transport::class, ServiceNotCreatedException::class, $transport);
        }
        $endpoint = $container->get($config['endpoint']);
        
        if (!is_callable($endpoint)) {
            throw $this->getException('endpoint', 'callable', ServiceNotCreatedException::class, $endpoint);
        }
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
