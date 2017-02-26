<?php

namespace ElasticsearchModuleTest\Traits\Tests;

use ElasticsearchModuleTest\Traits\EndpointDependencyTrait;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait ClientTrait
{
    use EndpointDependencyTrait,
        EndpointTrait;
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerWithClientDependencies(array $config)
    {
        $transportContainer = $this->getContainerWithTransportDependencies($this->getConfig());
        $endpointContainer = $this->getContainerWithEndpointDependencies($this->getConfig());
        $clientContainer = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($clientContainer, 'get', [
            'Config' => $config,
            'elasticsearch.transport.default' => $this->getTransport($transportContainer, 'default'),
            'elasticsearch.endpoint.default' => $this->getEndpoint($endpointContainer, 'default'),
        ]);
        return $clientContainer;
    }
}
