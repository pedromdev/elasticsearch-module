<?php

namespace ElasticsearchModuleTest\Traits\Tests;

use ElasticsearchModuleTest\Traits\TransportDependencyTrait;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait EndpointTrait
{
    use TransportDependencyTrait,
        TransportTrait;
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerWithEndpointDependencies(array $config)
    {
        $transportContainer = $this->getContainerWithTransportDependencies($this->getConfig());
        $endpointContainer = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($endpointContainer, 'get', [
            'Config' => $config,
            'elasticsearch.transport.default' => $this->getTransport($transportContainer, 'default'),
        ]);
        return $endpointContainer;
    }
}
