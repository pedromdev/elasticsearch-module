<?php

namespace ElasticsearchModule\Service;

use ArrayObject;
use Zend\Stdlib\ArrayObject as ZendArrayObject;
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
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        return ClientBuilder::defaultHandler(
            $this->getHandlerParams($serviceLocator, $config, 'multi_handler', 'handle_factory'),
            $this->getHandlerParams($serviceLocator, $config, 'single_handler', 'factory')
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
     * @param array|ArrayObject|ZendArrayObject $handlerConfig
     * @param string $paramKey
     * @param string $factoryKey
     * @return array
     */
    private function getHandlerParams(ServiceLocatorInterface $serviceLocator, $handlerConfig, $paramKey, $factoryKey)
    {
        if (!isset($handlerConfig['params'][$paramKey])) {
            return [];
        }
        return $this->createFactoryInConfig(
            $serviceLocator,
            $handlerConfig['params'][$paramKey],
            $factoryKey
        );
    }
    
    /**
     * @param string $factoryKey
     * @return array
     */
    private function createFactoryInConfig(ServiceLocatorInterface $serviceLocator, $handlerParams, $factoryKey)
    {
        if ($this->hasHandlerFactoryOption($handlerParams, $factoryKey)) {
            $serviceOrClass = $handlerParams[$factoryKey];
            $handlerParams[$factoryKey] = $this->getServiceOrClassObject($serviceLocator, $serviceOrClass);
            
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
