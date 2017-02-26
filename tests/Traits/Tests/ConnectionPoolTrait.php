<?php

namespace ElasticsearchModuleTest\Traits\Tests;

use ElasticsearchModuleTest\Traits\ConnectionFactoryDependencyTrait;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait ConnectionPoolTrait
{
    use ConnectionFactoryDependencyTrait,
        ConnectionFactoryTrait;
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerWithConnectionPoolDependencies(array $config)
    {
        $mock = $this->getContainerWithConnectionFactoryDependencies($this->getConfig());
        $mock2 = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($mock2, 'get', [
            'Config' => $config,
            'elasticsearch.connection_factory.default' => $this->getConnectionFactory($mock, 'default'),
        ]);
        $this->mockMappedReturn($mock2, 'has', ['elasticsearch.connection_factory.default' => true]);
        return $mock2;
    }
}
