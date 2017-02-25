<?php

namespace ElasticsearchModuleTest\Service;

use Elasticsearch\Connections\ConnectionFactoryInterface;
use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModule\Service\ConnectionFactory;
use ElasticsearchModuleTest\Mock\InvalidConnectionFactory;
use ElasticsearchModuleTest\Traits\Tests\ConnectionFactoryTrait;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ConnectionFactoryTest extends AbstractFactoryTest
{
    use ConnectionFactoryTrait;
    
    public function testCreateConnectionFactoryFromConfiguration()
    {
        $mock = $this->getContainerWithConnectionFactoryDependencies($this->getConfig());
        $factory = new ConnectionFactory('default');
        
        $connectionFactory = $factory->createService($mock);
        
        $this->assertInstanceOf(ConnectionFactoryInterface::class, $connectionFactory);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowsExceptionWhenConnectionFactoryIsInvalid()
    {
        $mock = $this->getContainerWithConnectionFactoryDependencies([
            'elasticsearch' => [
                'connection_factory' => [
                    'default' => [
                        'factory' => InvalidConnectionFactory::class,
                        'handler' => 'elasticsearch.handler.default',
                        'params' => [],
                        'serializer' => SmartSerializer::class,
                        'loggers' => 'elasticsearch.loggers.default',
                    ],
                ],
            ],
        ]);
        $factory = new ConnectionFactory('default');
        
        $factory->createService($mock);
    }
}
