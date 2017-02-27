<?php

namespace ElasticsearchModuleTest\Service;

use Elasticsearch\Client;
use ElasticsearchModule\Service\ClientFactory;
use ElasticsearchModuleTest\Traits\Tests\ClientTrait;
use stdClass;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ClientFactoryTest extends AbstractFactoryTest
{
    use ClientTrait;
    
    public function testCreateClientFromConfiguration()
    {
        $clientContainer = $this->getContainerWithClientDependencies($this->getConfig());
        $factory = new ClientFactory('default');
        
        $client = $factory->createService($clientContainer);
        
        $this->assertInstanceOf(Client::class, $client);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowExceptionWhenTransportVariableIsNotAnInstanceOfElasticsearchTransport()
    {
        $config = $this->getConfig();
        $endpointContainer = $this->getContainerWithEndpointDependencies($config);
        $clientContainer = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($clientContainer, 'get', [
            'Config' => $config,
            'elasticsearch.transport.default' => new stdClass(),
            'elasticsearch.endpoint.default' => $this->getEndpoint($endpointContainer, 'default'),
        ]);
        $factory = new ClientFactory('default');
        
        $factory->createService($clientContainer);
    }
}
