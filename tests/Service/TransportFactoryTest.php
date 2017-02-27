<?php

namespace ElasticsearchModuleTest\Service;

use Elasticsearch\Transport;
use ElasticsearchModule\Service\TransportFactory;
use ElasticsearchModuleTest\Traits\Tests\TransportTrait;
use stdClass;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class TransportFactoryTest extends AbstractFactoryTest
{
    use TransportTrait;
    
    public function testCreateTransportFromConfiguration()
    {
        $transportContainer = $this->getContainerWithTransportDependencies($this->getConfig());
        $factory = new TransportFactory('default');
        
        $transport = $factory->createService($transportContainer);
        
        $this->assertInstanceOf(Transport::class, $transport);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testThrowExceptionWhenConnectionPoolVariableIsNotAnInstanceOfAbstractConnectionPool()
    {
        $config = $this->getConfig();
        $loggerContainer = $this->getContainerWithLoggersDependencies($config);
        $transportContainer = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($transportContainer, 'get', [
            'Config' => $config,
            'elasticsearch.connection_pool.default' => new stdClass(),
            'elasticsearch.loggers.default' => $this->getLoggers($loggerContainer, 'default'),
        ]);
        $factory = new TransportFactory('default');
        
        $factory->createService($transportContainer);
    }
}
