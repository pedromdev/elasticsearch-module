<?php

namespace ElasticsearchModuleTest\Service;

use ArrayObject;
use Elasticsearch\Connections\ConnectionFactoryInterface;
use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModule\Service\ConnectionFactory;
use ElasticsearchModule\Service\HandlerFactory;
use ElasticsearchModule\Service\LoggersFactory;
use ElasticsearchModuleTest\Mock\InvalidConnectionFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ConnectionFactoryTest extends AbstractFactoryTest
{
    
    public function testCreateConnectionFactoryFromConfiguration()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $mock2 = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($mock2, 'get', [
            'Config' => $this->getConfig(),
            'elasticsearch.handler.default' => $this->getHandlerFromConfigurantion($mock, 'default'),
            'elasticsearch.loggers.default' => $this->getLoggersFromConfigurantion($mock, 'default'),
        ]);
        $this->mockMappedReturn($mock2, 'has', [
            SmartSerializer::class => false,
        ]);
        $factory = new ConnectionFactory('default');
        
        $connectionFactory = $factory->createService($mock2);
        
        $this->assertInstanceOf(ConnectionFactoryInterface::class, $connectionFactory);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowsExceptionWhenConnectionFactoryIsInvalid()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $mock2 = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($mock2, 'get', [
            'Config' => [
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
            ],
            'elasticsearch.handler.default' => $this->getHandlerFromConfigurantion($mock, 'default'),
            'elasticsearch.loggers.default' => $this->getLoggersFromConfigurantion($mock, 'default'),
        ]);
        $this->mockMappedReturn($mock2, 'has', [
            SmartSerializer::class => false,
        ]);
        $factory = new ConnectionFactory('default');
        
        $factory->createService($mock2);
    }
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return callable
     */
    private function getHandlerFromConfigurantion(ServiceLocatorInterface $serviceLocator, $name)
    {
        $factory = new HandlerFactory($name);
        return $factory->createService($serviceLocator);
    }
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return ArrayObject
     */
    private function getLoggersFromConfigurantion(ServiceLocatorInterface $serviceLocator, $name)
    {
        $factory = new LoggersFactory($name);
        return $factory->createService($serviceLocator);
    }
}
