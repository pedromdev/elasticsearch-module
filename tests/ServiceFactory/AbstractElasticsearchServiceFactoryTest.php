<?php

namespace ElasticsearchModuleTest\ServiceFactory;

use ArrayObject;
use Elasticsearch\Client;
use Elasticsearch\ConnectionPool\AbstractConnectionPool;
use Elasticsearch\Connections\ConnectionFactoryInterface;
use Elasticsearch\Transport;
use ElasticsearchModuleTest\Service\AbstractTest;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class AbstractElasticsearchServiceFactoryTest extends AbstractTest
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;
    
    protected function setUp()
    {
        parent::setUp();
        $config = $this->getConfig();
        $this->serviceManager = new ServiceManager(new Config($config['service_manager']));
        $this->serviceManager->setService('Config', $config);
    }
    
    public function testCreateAllServicesFromConfiguration()
    {
        $loggers            = $this->serviceManager->get('elasticsearch.loggers.default');
        $handler            = $this->serviceManager->get('elasticsearch.handler.default');
        $connectionFactory  = $this->serviceManager->get('elasticsearch.connection_factory.default');
        $connectionPool     = $this->serviceManager->get('elasticsearch.connection_pool.default');
        $transport          = $this->serviceManager->get('elasticsearch.transport.default');
        $endpoint           = $this->serviceManager->get('elasticsearch.endpoint.default');
        $client             = $this->serviceManager->get('elasticsearch.client.default');
        $clientByClass      = $this->serviceManager->get(Client::class);
        
        $this->assertInstanceOf(ArrayObject::class, $loggers);
        $this->assertTrue(is_callable($handler), 'The handler is not callable');
        $this->assertInstanceOf(ConnectionFactoryInterface::class, $connectionFactory);
        $this->assertInstanceOf(AbstractConnectionPool::class, $connectionPool);
        $this->assertInstanceOf(Transport::class, $transport);
        $this->assertTrue(is_callable($endpoint), 'The endpoint is not callable');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(Client::class, $clientByClass);
    }
    
    /**
     * @param string $serviceName
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @dataProvider inexistentElasticsearchServicesProvider
     */
    public function testThrowExceptionWhenNotFindAnElasticsearchService($serviceName)
    {
        $this->serviceManager->get($serviceName);
    }
    
    public function inexistentElasticsearchServicesProvider()
    {
        return [
            ['elasticsearch.loggers2.default'],
            ['elasticsearch.handler2.default'],
            ['elasticsearch.connection_factory2.default'],
            ['elasticsearch.connection_pool2.default'],
            ['elasticsearch.transport2.default'],
            ['elasticsearch.endpoint2.default'],
            ['elasticsearch.client2.default'],
        ];
    }
}
