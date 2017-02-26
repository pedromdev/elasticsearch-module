<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\Serializers\SerializerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class EndpointFactory extends AbstractFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $serializer = $this->getServiceOrClassObject($serviceLocator, $config['serializer']);
        
        if (!$serializer instanceof SerializerInterface) {
            throw new ServiceNotCreatedException(sprintf(
                "The serializer must be instance of %s, %s given",
                SerializerInterface::class,
                is_object($serializer) ? get_class($serializer) : gettype($serializer)
            ));
        }
        $transport = $serviceLocator->get($config['transport']);
        
        return function ($class) use ($transport, $serializer) {
            $endpointClass = "\\Elasticsearch\\Endpoints\\$class";
            
            if (in_array($class, ['Bulk', 'MSearch', 'MPercolate'])) {
                return new $endpointClass($transport, $serializer);
            } else {
                return new $endpointClass($transport);
            }
        };
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'endpoint';
    }
}