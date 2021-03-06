<?php

namespace ElasticsearchModuleTest\Service;

use Elasticsearch\Endpoints\AbstractEndpoint;
use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModule\Container\Endpoints;
use ElasticsearchModule\Service\EndpointFactory;
use ElasticsearchModuleTest\Traits\Tests\EndpointTrait;
use stdClass;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class EndpointFactoryTest extends AbstractFactoryTest
{
    use EndpointTrait;
    
    public function testCreateEndpointFromConfiguration()
    {
        $endpointContainer = $this->getContainerWithEndpointDependencies($this->getConfig());
        $factory = new EndpointFactory('default');
        
        $endpoint = $factory->createService($endpointContainer);
        
        $this->assertTrue(is_callable($endpoint), 'The endpoint is not callable');
        $this->assertInstanceOf(AbstractEndpoint::class, $endpoint('Bulk'));
        $this->assertInstanceOf(AbstractEndpoint::class, $endpoint('Get'));
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowExceptionWhenFindAnInvalidSerializer()
    {
        $endpointContainer = $this->getContainerWithEndpointDependencies([
            'elasticsearch' => [
                'endpoint' => [
                    'default' => [
                        'container' => Endpoints::class,
                        'serializer' => stdClass::class,
                        'transport' => 'elasticsearch.transport.default',
                    ],
                ],
            ],
        ]);
        $factory = new EndpointFactory('default');
        
        $factory->createService($endpointContainer);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowExceptionWhenAnInvalidEndpointContainer()
    {
        $endpointContainer = $this->getContainerWithEndpointDependencies([
            'elasticsearch' => [
                'endpoint' => [
                    'default' => [
                        'container' => stdClass::class,
                        'serializer' => SmartSerializer::class,
                        'transport' => 'elasticsearch.transport.default',
                    ],
                ],
            ],
        ]);
        $factory = new EndpointFactory('default');
        
        $endpoint = $factory->createService($endpointContainer);
    }
}
