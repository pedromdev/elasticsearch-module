<?php

namespace ElasticsearchModuleTest\Traits;

use ArrayObject;
use ElasticsearchModule\Service\LoggersFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait LoggersDependencyTrait
{
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return ArrayObject
     */
    private function getLoggers(ServiceLocatorInterface $serviceLocator, $name)
    {
        $factory = new LoggersFactory($name);
        return $factory->createService($serviceLocator);
    }
}
