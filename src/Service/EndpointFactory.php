<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\Serializers\SerializerInterface;
use ElasticsearchModule\Container\EndpointsInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class EndpointFactory extends AbstractFactory
{
    use InvalidTypeExceptionTrait;
    
    /**
     * {@inheritDoc}
     */
    protected function create(ContainerInterface $container, $config)
    {
        $serializer = $this->getServiceOrClassObject($container, $config['serializer']);
        
        if (!$serializer instanceof SerializerInterface) {
            throw $this->getException(
                'serializer',
                SerializerInterface::class,
                ServiceNotCreatedException::class,
                $serializer
            );
        }
        $transport = $container->get($config['transport']);
        $endpointContainer = $config['container'];
        $endpointContainer = new $endpointContainer($transport, $serializer);
        
        if (!$endpointContainer instanceof EndpointsInterface) {
            throw $this->getException(
                'container',
                EndpointsInterface::class,
                ServiceNotCreatedException::class,
                $endpointContainer
            );
        }
        
        return $endpointContainer;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'endpoint';
    }
}