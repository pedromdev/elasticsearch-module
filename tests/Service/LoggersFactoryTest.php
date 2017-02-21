<?php

namespace ElasticsearchModuleTest\Service;

use ArrayObject;
use ElasticsearchModule\Service\LoggersFactory;
use Psr\Log\LoggerInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class LoggersFactoryTest extends AbstractFactoryTest
{
    
    public function testCreateLoggersFromConfiguration()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $factory = new LoggersFactory('default');
        
        $loggers = $factory->createService($mock);
        
        $this->assertInstanceOf(ArrayObject::class, $loggers);
        $this->assertInstanceOf(LoggerInterface::class, $loggers['logger']);
        $this->assertInstanceOf(LoggerInterface::class, $loggers['tracer']);
    }
}
