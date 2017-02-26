<?php

namespace ElasticsearchModuleTest\Traits\Tests;

use ElasticsearchModuleTest\Traits\ConnectionPoolDependencyTrait;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait TransportTrait
{
    use ConnectionPoolDependencyTrait,
        ConnectionPoolTrait;
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerWithTransportDependencies(array $config)
    {
        $connectionPoolContainer = $this->getContainerWithConnectionPoolDependencies($this->getConfig());
        $loggerContainer = $this->getContainerWithLoggersDependencies($this->getConfig());
        $transportContainer = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($transportContainer, 'get', [
            'Config' => $config,
            'elasticsearch.connection_pool.default' => $this->getConnectionPool($connectionPoolContainer, 'default'),
            'elasticsearch.loggers.default' => $this->getLoggers($loggerContainer, 'default'),
        ]);
        return $transportContainer;
    }
}
