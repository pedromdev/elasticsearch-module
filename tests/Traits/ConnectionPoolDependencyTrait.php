<?php

namespace ElasticsearchModuleTest\Traits;

use Elasticsearch\ConnectionPool\AbstractConnectionPool;
use ElasticsearchModule\Service\ConnectionPoolFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait ConnectionPoolDependencyTrait
{
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return AbstractConnectionPool
     */
    public function getConnectionPool(ServiceLocatorInterface $serviceLocator, $name)
    {
        $factory = new ConnectionPoolFactory($name);
        return $factory->createService($serviceLocator);
    }
}
