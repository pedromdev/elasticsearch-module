<?php

namespace ElasticsearchModuleTest\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
abstract class AbstractLogFactoryTest extends AbstractFactoryTest
{
    public function testCreateLoggerFromConfiguration()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $factory = $this->getLogFactory('default');
        
        $logger = $factory->createService($mock);
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
    
    public function testCreateLoggerFromServiceLocator()
    {
        $mock = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($mock, 'has', [NullLogger::class => true]);
        $this->mockMappedReturn($mock, 'get', [
            'Config' => $this->getConfig(),
            NullLogger::class => new NullLogger(),
        ]);
        $factory = $this->getLogFactory('default');
        
        $logger = $factory->createService($mock);
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @expectedExceptionMessage elasticserach.loggers.exception could not be found
     */
    public function testThrowsExceptionWhenNotFindTheLogConfig()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $factory = $this->getLogFactory('exception');
        
        $factory->createService($mock);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @expectedExceptionMessage There is no 'not-found' log
     */
    public function testThrowsExceptionWhenNotFindLog()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($mock, 'get', [
            'Config' => [
                'elasticsearch' => [
                    'loggers' => [
                        'unit-test' => [
                            'logger' => 'not-found',
                            'tracer' => 'not-found',
                        ],
                    ],
                ],
            ],
        ]);
        $factory = $this->getLogFactory('unit-test');
        
        $factory->createService($mock);
    }
    
    /**
     * 
     * @param \ElasticsearchModule\Service\AbstractLogFactory
     */
    abstract protected function getLogFactory($name);
}
