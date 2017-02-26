<?php

namespace ElasticsearchModuleTest\Traits;

use Elasticsearch\Transport;
use ElasticsearchModule\Service\TransportFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait TransportDependencyTrait
{
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return Transport
     */
    public function getTransport(ServiceLocatorInterface $serviceLocator, $name)
    {
        $factory = new TransportFactory($name);
        return $factory->createService($serviceLocator);
    }
}
