<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\ClientBuilder;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class HandlerFactory extends AbstractFactory
{

    /**
     * {@inheritDoc}
     */
    public function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        return ClientBuilder::defaultHandler(
            $this->getMultiHandlerParams($serviceLocator, $config),
            $this->getSingleHandlerParams($serviceLocator, $config)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'handler';
    }
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array $handlerConfig
     * @return array
     */
    private function getMultiHandlerParams(ServiceLocatorInterface $serviceLocator, array $handlerConfig)
    {
        if (!isset($handlerConfig['params']['multi_handler'])) {
            return [];
        }
        return $this->createFactoryInConfig(
            $serviceLocator,
            $handlerConfig['params']['multi_handler'],
            'handle_factory'
        );
    }
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array $handlerConfig
     * @return array
     */
    private function getSingleHandlerParams(ServiceLocatorInterface $serviceLocator, array $handlerConfig)
    {
        if (!isset($handlerConfig['params']['single_handler'])) {
            return [];
        }
        return $this->createFactoryInConfig(
            $serviceLocator,
            $handlerConfig['params']['single_handler'],
            'factory'
        );
    }
    
    private function createFactoryInConfig(ServiceLocatorInterface $serviceLocator, $handlerParams, $factoryKey)
    {
        if ($this->hasHandlerFactoryOption($handlerParams, $factoryKey)) {
            if ($serviceLocator->has($handlerParams[$factoryKey])) {
                $handlerParams[$factoryKey] = $serviceLocator->get($handlerParams[$factoryKey]);
            } else if (class_exists($handlerParams[$factoryKey])) {
                $factoryClass = $handlerParams[$factoryKey];
                $handlerParams[$factoryKey] = new $factoryClass();
            }
            
            if (!is_callable($handlerParams[$factoryKey])) {
                unset($handlerParams[$factoryKey]);
            }
        }
        return $handlerParams;
    }
    
    /**
     * @param array $handlerParams
     * @param string $factoryKey
     * @return bool
     */
    private function hasHandlerFactoryOption($handlerParams, $factoryKey)
    {
        return isset($handlerParams[$factoryKey]) && is_string($handlerParams[$factoryKey]);
    }
}
