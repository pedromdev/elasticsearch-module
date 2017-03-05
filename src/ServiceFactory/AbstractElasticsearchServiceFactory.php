<?php

namespace ElasticsearchModule\ServiceFactory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class AbstractElasticsearchServiceFactory implements AbstractFactoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $factory = $this->getFactory($container, $requestedName);
        return $factory($container, $requestedName);
    }

    /**
     * {@inheritDoc}
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return $this->getFactory($container, $requestedName) !== null;
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return FactoryInterface
     */
    private function getFactory(ContainerInterface $container, $requestedName)
    {
        $pattern = "/^elasticsearch\.(?P<serviceType>[a-z0-9_-]+)\.(?P<serviceName>[a-z0-9_-]+)$/";
        $matches = [];
        
        if (!preg_match($pattern, $requestedName, $matches)) {
            return null;
        }
        $serviceType = $matches['serviceType'];
        $serviceName = $matches['serviceName'];
        $config = $container->get('Config');
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
