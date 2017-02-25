<?php

namespace ElasticsearchModuleTest\Service;

use ArrayObject;
use Elasticsearch\ConnectionPool\AbstractConnectionPool;
use Elasticsearch\Connections\ConnectionFactoryInterface;
use Elasticsearch\Serializers\SmartSerializer;
use ElasticsearchModule\Service\ConnectionFactory;
use ElasticsearchModule\Service\ConnectionPoolFactory;
use ElasticsearchModule\Service\HandlerFactory;
use ElasticsearchModule\Service\LoggersFactory;
use ElasticsearchModuleTest\Mock\InvalidConnectionPool;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ConnectionPoolFactoryTest extends AbstractFactoryTest
{
    
    /**
     * @param array $config
     * @dataProvider connectionPoolConfigurationProvider
     */
    public function testCreateConnectionPoolFromConfiguration(array $config)
    {
        $mock = $this->mockConnectionPoolDependencies($config);
        $factory = new ConnectionPoolFactory('default');
        
        $connectionPool = $factory->createService($mock);
        
        $this->assertInstanceOf(AbstractConnectionPool::class, $connectionPool);
    }
    
    /**
     * @param array $config
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @dataProvider invalidHostsProvider
     */
    public function testThrowExceptionWhenHostHasInvalidType(array $config)
    {
        $mock = $this->mockConnectionPoolDependencies($config);
        $factory = new ConnectionPoolFactory('default');
        
        $factory->createService($mock);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowExceptionWhenAHostIsNotFound()
    {
        $config = $this->getConfigWithHosts([[
            'scheme' => 'http',
            'port' => 9200,
        ]]);
        $mock = $this->mockConnectionPoolDependencies($config);
        $factory = new ConnectionPoolFactory('default');
        
        $factory->createService($mock);
    }
    
    /**
     * @param string $malformedURL
     * @expectedException \Zend\ServiceManager\Exception\InvalidArgumentException
     * @dataProvider malformedURLs
     */
    public function testThrowExceptionWhenURLIsMalformed($malformedURL)
    {
        $this->markTestIncomplete('This test cannot provide malformed URLs');
        $config = $this->getConfigWithHosts([$malformedURL]);
        $mock = $this->mockConnectionPoolDependencies($config);
        $factory = new ConnectionPoolFactory('default');
        
        $factory->createService($mock);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowExceptionWhenNotExtendsAbstractConnectionPool()
    {
        
        $config = $this->getConfigWithInvalidPoolClass();
        $mock = $this->mockConnectionPoolDependencies($config);
        $factory = new ConnectionPoolFactory('default');
        
        $factory->createService($mock);
    }
    
    /**
     * @return array
     */
    public function connectionPoolConfigurationProvider()
    {
        return [
            [$this->getConfig()],
            [$this->getConfigWithHosts([
                'http://localhost:9200',
                'http://localhost',
                ['host' => 'localhost'],
                [
                    'host' => 'localhost',
                    'scheme' => 'https',
                    'port' => '9300',
                ],
            ])],
        ];
    }
    
    /**
     * @return array
     */
    public function invalidHostsProvider()
    {
        return [
            [$this->getConfigWithHosts([null])],
            [$this->getConfigWithHosts([1])],
            [$this->getConfigWithHosts([1.5])],
            [$this->getConfigWithHosts([new stdClass()])],
            [$this->getConfigWithHosts([false])],
            [$this->getConfigWithHosts([true])],
        ];
    }
    
    /**
     * @return array
     */
    public function malformedURLs()
    {
        return [
            ["http://ççççç:80loa"],
        ];
    }
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function mockConnectionPoolDependencies(array $config)
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
        $mock3 = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($mock3, 'get', [
            'Config' => $config,
            'elasticsearch.connection_factory.default' => $this->getConnectionFactory($mock2),
        ]);
        $this->mockMappedReturn($mock3, 'has', ['elasticsearch.connection_factory.default' => true]);
        return $mock3;
    }
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ConnectionFactoryInterface
     */
    private function getConnectionFactory($serviceLocator)
    {
        $factory = new ConnectionFactory('default');
        return $factory->createService($serviceLocator);
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
    
    /**
     * @param array $hosts
     * @return array
     */
    private function getConfigWithHosts(array $hosts)
    {
        return ArrayUtils::merge($this->getConfig(), [
            'elasticsearch' => [
                'connection_pool' => [
                    'default' => [
                        'hosts' => $hosts,
                    ],
                ],
            ]
        ]);
    }
    
    /**
     * @return array
     */
    private function getConfigWithInvalidPoolClass()
    {
        return ArrayUtils::merge($this->getConfig(), [
            'elasticsearch' => [
                'connection_pool' => [
                    'default' => [
                        'class' => InvalidConnectionPool::class,
                    ],
                ],
            ]
        ]);
    }
}
