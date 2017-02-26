<?php

namespace ElasticsearchModuleTest\Service;

use Elasticsearch\ConnectionPool\AbstractConnectionPool;
use ElasticsearchModule\Service\ConnectionPoolFactory;
use ElasticsearchModuleTest\Mock\InvalidConnectionPool;
use ElasticsearchModuleTest\Traits\Tests\ConnectionPoolTrait;
use stdClass;
use Zend\Stdlib\ArrayUtils;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ConnectionPoolFactoryTest extends AbstractFactoryTest
{
    use ConnectionPoolTrait;
    
    /**
     * @param array $config
     * @dataProvider connectionPoolConfigurationProvider
     */
    public function testCreateConnectionPoolFromConfiguration(array $config)
    {
        $mock = $this->getContainerWithConnectionPoolDependencies($config);
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
        $mock = $this->getContainerWithConnectionPoolDependencies($config);
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
        $mock = $this->getContainerWithConnectionPoolDependencies($config);
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
        $mock = $this->getContainerWithConnectionPoolDependencies($config);
        $factory = new ConnectionPoolFactory('default');
        
        $factory->createService($mock);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowExceptionWhenNotExtendsAbstractConnectionPool()
    {
        
        $config = $this->getConfigWithInvalidPoolClass();
        $mock = $this->getContainerWithConnectionPoolDependencies($config);
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
