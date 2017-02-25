<?php

namespace ElasticsearchModuleTest\Traits;

use Elasticsearch\Connections\ConnectionFactoryInterface;
use ElasticsearchModule\Service\ConnectionFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait ConnectionFactoryDependencyTrait
{
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return ConnectionFactoryInterface
     */
    private function getConnectionFactory(ServiceLocatorInterface $serviceLocator, $name)
    {
        $factory = new ConnectionFactory($name);
        return $factory->createService($serviceLocator);
    }
}
