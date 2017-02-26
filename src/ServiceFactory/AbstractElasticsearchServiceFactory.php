<?php

namespace ElasticsearchModule\ServiceFactory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class AbstractElasticsearchServiceFactory implements AbstractFactoryInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getFactory($serviceLocator, $requestedName) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $factory = $this->getFactory($serviceLocator, $requestedName);
        return $factory->createService($serviceLocator);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $requestedName
     * @return FactoryInterface
     */
    private function getFactory(ServiceLocatorInterface $serviceLocator, $requestedName)
    {
        $pattern = "/^elasticsearch\.(?P<serviceType>[a-z0-9_-]+)\.(?P<serviceName>[a-z0-9_-]+)$/";
        $matches = [];
        
        if (!preg_match($pattern, $requestedName, $matches)) {
            return null;
        }
        $serviceType = $matches['serviceType'];
        $serviceName = $matches['serviceName'];
        $config = $serviceLocator->get('Config');
        $elasticsearchModuleConfig = $config['elasticsearch'];
        
        if (!isset($elasticsearchModuleConfig[$serviceType]) ||
            !isset($config['elasticsearch_factories'][$serviceType])
        ) {
            return null;
        }
        $factoryClass = $config['elasticsearch_factories'][$serviceType];
        return new $factoryClass($serviceName);
    }
}
