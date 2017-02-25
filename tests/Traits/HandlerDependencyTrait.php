<?php

namespace ElasticsearchModuleTest\Traits;

use ElasticsearchModule\Service\HandlerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait HandlerDependencyTrait
{
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return callable
     */
    private function getHandler(ServiceLocatorInterface $serviceLocator, $name)
    {
        $factory = new HandlerFactory($name);
        return $factory->createService($serviceLocator);
    }
}
