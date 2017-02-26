<?php

namespace ElasticsearchModuleTest\Traits\Tests;

use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModuleTest\Traits\HandlerDependencyTrait;
use ElasticsearchModuleTest\Traits\LoggersDependencyTrait;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait ConnectionFactoryTrait
{
    use HandlerDependencyTrait,
        LoggersDependencyTrait,
        HandlerTrait,
        LoggersTrait;
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerWithConnectionFactoryDependencies(array $config)
    {
        $loggersContainer = $this->getContainerWithLoggersDependencies($this->getConfig());
        $handlerContainer = $this->getContainerWithHandlerDependencies($this->getConfig());
        
        $connectionFactoryContainer = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($connectionFactoryContainer, 'get', [
            'Config' => $config,
            'elasticsearch.handler.default' => $this->getHandler($handlerContainer, 'default'),
            'elasticsearch.loggers.default' => $this->getLoggers($loggersContainer, 'default'),
        ]);
        $this->mockMappedReturn($connectionFactoryContainer, 'has', [
            SmartSerializer::class => false,
        ]);
        return $connectionFactoryContainer;
    }
}
