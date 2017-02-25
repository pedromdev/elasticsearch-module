<?php

namespace ElasticsearchModule\Service;

use ArrayObject;
use Elasticsearch\Connections\ConnectionFactoryInterface;
use Elasticsearch\Serializers\SerializerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ConnectionFactory extends AbstractFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $factoryName = $config['factory'];
        $handler = $serviceLocator->get($config['handler']);
        $loggers = $serviceLocator->get($config['loggers']);
        $serializer = $this->getServiceOrClassObject($serviceLocator, $config['serializer']);
        $params = $this->getConnectionParametersFromConfiguration($config);
        $connectionFactory = new $factoryName($handler, $params, $serializer, $loggers['logger'], $loggers['tracer']);
        
        if (! $connectionFactory instanceof ConnectionFactoryInterface) {
            throw new ServiceNotCreatedException(sprintf(
                "The '%s' class does not implements %s",
                $factoryName,
                ConnectionFactoryInterface::class
            ));
        }
        return $connectionFactory->create($config['host_details']);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'connection';
    }
    
    /**
     * @param array|ArrayObject $config
     * @return array
     */
    private function getConnectionParametersFromConfiguration($config)
    {
        return (isset($config['params']) && is_array($config['params'])) ? $config['params'] : [];
    }

}
