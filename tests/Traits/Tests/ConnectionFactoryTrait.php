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
    use HandlerDependencyTrait;
    use LoggersDependencyTrait;
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerWithConnectionFactoryDependencies(array $config)
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $mock2 = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($mock2, 'get', [
            'Config' => $config,
            'elasticsearch.handler.default' => $this->getHandler($mock, 'default'),
            'elasticsearch.loggers.default' => $this->getLoggers($mock, 'default'),
        ]);
        $this->mockMappedReturn($mock2, 'has', [
            SmartSerializer::class => false,
        ]);
        return $mock2;
    }
}
