<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\Serializers\SerializerInterface;
use ElasticsearchModule\Container\EndpointsInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class EndpointFactory extends AbstractFactory
{
    use InvalidTypeExceptionTrait;
    
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $serializer = $this->getServiceOrClassObject($serviceLocator, $config['serializer']);
        
        if (!$serializer instanceof SerializerInterface) {
            throw $this->getException(
                'serializer',
                SerializerInterface::class,
                ServiceNotCreatedException::class,
                $serializer
            );
        }
        $transport = $serviceLocator->get($config['transport']);
        $container = $config['container'];
        $container = new $container($transport, $serializer);
        
        if (!$container instanceof EndpointsInterface) {
            throw $this->getException(
                'container',
                EndpointsInterface::class,
                ServiceNotCreatedException::class,
                $container
            );
        }
        
        return $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'endpoint';
    }
}