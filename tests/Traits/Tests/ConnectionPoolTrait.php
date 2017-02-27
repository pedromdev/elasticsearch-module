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
        $connectionFactoryContainer = $this->getContainerWithConnectionFactoryDependencies($this->getConfig());
        $connectionPoolContainer = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($connectionPoolContainer, 'get', [
            'Config' => $config,
            'elasticsearch.connection_factory.default' => $this->getConnectionFactory($connectionFactoryContainer, 'default'),
        ]);
        $this->mockMappedReturn($connectionPoolContainer, 'has', ['elasticsearch.connection_factory.default' => true]);
        return $connectionPoolContainer;
    }
}
