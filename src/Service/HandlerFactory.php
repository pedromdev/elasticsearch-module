<?php

namespace ElasticsearchModule\Service;

use ArrayObject;
use Elasticsearch\ClientBuilder;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerInterface as ContainerInterface2;
use Zend\Stdlib\ArrayObject as ZendArrayObject;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class HandlerFactory extends AbstractFactory
{

    /**
     * {@inheritDoc}
     */
    protected function create(ContainerInterface $container, $config)
    {
        return ClientBuilder::defaultHandler(
            $this->getHandlerParams($container, $config, 'multi_handler', 'handle_factory'),
            $this->getHandlerParams($container, $config, 'single_handler', 'factory')
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
     * @param ContainerInterface2 $container
     * @param array|ArrayObject|ZendArrayObject $handlerConfig
     * @param string $paramKey
     * @param string $factoryKey
     * @return array
     */
    private function getHandlerParams(ContainerInterface $container, $handlerConfig, $paramKey, $factoryKey)
    {
        if (!isset($handlerConfig['params'][$paramKey])) {
            return [];
        }
        return $this->createFactoryInConfig(
            $container,
            $handlerConfig['params'][$paramKey],
            $factoryKey
        );
    }
    
    /**
     * @param ContainerInterface $container
     * @param array $handlerParams
     * @param string $factoryKey
     * @return array
     */
    private function createFactoryInConfig(ContainerInterface $container, $handlerParams, $factoryKey)
    {
        if ($this->hasHandlerFactoryOption($handlerParams, $factoryKey)) {
            $serviceOrClass = $handlerParams[$factoryKey];
            $handlerParams[$factoryKey] = $this->getServiceOrClassObject($container, $serviceOrClass);
            
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
