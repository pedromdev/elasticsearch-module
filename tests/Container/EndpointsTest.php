<?php

namespace ElasticsearchModuleTest\Container;

use Elasticsearch\Endpoints\AbstractEndpoint;
use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModule\Container\Endpoints;
use ElasticsearchModuleTest\Service\AbstractTest;
use ElasticsearchModuleTest\Traits\Tests\EndpointTrait;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class EndpointsTest extends AbstractTest
{
    use EndpointTrait;
    
    private $endpoints;
    
    protected function setUp()
    {
        parent::setUp();
        
        $transportContainer = $this->getContainerWithTransportDependencies($this->getConfig());
        $transport = $this->getTransport($transportContainer, 'default');
        $serializer = new SmartSerializer();
        $this->endpoints = new Endpoints($transport, $serializer);
    }
    
    public function testCreateEndpointsInstances()
    {
        $endpoints = $this->endpoints;
        
        $bulkEndpoint = $endpoints('Bulk');
        $getEndpoint = $endpoints('Get');
        $anotherGetEndpoint = $endpoints('Get');
        
        $this->assertInstanceOf(AbstractEndpoint::class, $bulkEndpoint);
        $this->assertInstanceOf(AbstractEndpoint::class, $getEndpoint);
        $this->assertSame($getEndpoint, $anotherGetEndpoint);
    }
    
    /**
     * @expectedException \ElasticsearchModule\Container\Exception\EndpointNotFoundException
     */
    public function testThrowExceptionWhenNotFindAnEndpoint()
    {
        $endpoints = $this->endpoints;
        
        $endpoints('EndpointNotFound');
    }
}
