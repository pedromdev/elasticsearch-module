<?php

namespace ElasticsearchModule\Service;

use ArrayObject;
use Elasticsearch\Connections\ConnectionFactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\Stdlib\ArrayObject as ZendArrayObject;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ConnectionFactory extends AbstractFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function create(ContainerInterface $container, $config)
    {
        $factoryName = $config['factory'];
        $handler = $container->get($config['handler']);
        $loggers = $container->get($config['loggers']);
        $serializer = $this->getServiceOrClassObject($container, $config['serializer']);
        $params = $this->getConnectionParametersFromConfiguration($config);
        $connectionFactory = new $factoryName($handler, $params, $serializer, $loggers['logger'], $loggers['tracer']);
        
        if (!$connectionFactory instanceof ConnectionFactoryInterface) {
            throw new ServiceNotCreatedException(sprintf(
                "The '%s' class does not implements %s",
                $factoryName,
                ConnectionFactoryInterface::class
            ));
        }
        return $connectionFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'connection_factory';
    }
    
    /**
     * @param array|ArrayObject|ZendArrayObject $config
     * @return array
     */
    private function getConnectionParametersFromConfiguration($config)
    {
        return (isset($config['params']) && is_array($config['params'])) ? $config['params'] : [];
    }

}
