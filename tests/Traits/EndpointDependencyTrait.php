<?php

namespace ElasticsearchModuleTest\Traits;

use ElasticsearchModule\Service\EndpointFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait EndpointDependencyTrait
{
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return callable
     */
    public function getEndpoint(ServiceLocatorInterface $serviceLocator, $name)
    {
        $factory = new EndpointFactory($name);
        return $factory->createService($serviceLocator);
    }
}
