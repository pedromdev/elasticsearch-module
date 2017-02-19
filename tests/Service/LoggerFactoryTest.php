<?php

namespace ElasticsearchModuleTest\Service;

use ElasticsearchModule\Service\LoggerFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class LoggerFactoryTest extends AbstractFactoryTest
{
    
    public function testCreateLoggerFromConfiguration()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $factory = new LoggerFactory('default');
        
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
        $factory = new LoggerFactory('default');
        
        $logger = $factory->createService($mock);
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @expectedExceptionMessage elasticserach.log.exception could not be found
     */
    public function testThrowsExceptionWhenNotFindTheLogConfig()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $factory = new LoggerFactory('exception');
        
        $factory->createService($mock);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @expectedExceptionMessage There is no 'not-found' logger
     */
    public function testThrowsExceptionWhenNotFindLog()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($mock, 'get', [
            'Config' => [
                'elasticsearch' => [
                    'log' => [
                        'unit-test' => [
                            'logger' => 'not-found',
                        ],
                    ],
                ],
            ],
        ]);
        $factory = new LoggerFactory('unit-test');
        
        $factory->createService($mock);
    }
    
}
