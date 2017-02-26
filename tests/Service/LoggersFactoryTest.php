<?php

namespace ElasticsearchModuleTest\Service;

use ArrayObject;
use ElasticsearchModule\Service\LoggersFactory;
use ElasticsearchModuleTest\Traits\Tests\LoggersTrait;
use Psr\Log\LoggerInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class LoggersFactoryTest extends AbstractFactoryTest
{
    use LoggersTrait;
    
    public function testCreateLoggersFromConfiguration()
    {
        $loggersContainer = $this->getContainerWithLoggersDependencies($this->getConfig());
        $factory = new LoggersFactory('default');
        
        $loggers = $factory->createService($loggersContainer);
        
        $this->assertInstanceOf(ArrayObject::class, $loggers);
        $this->assertInstanceOf(LoggerInterface::class, $loggers['logger']);
        $this->assertInstanceOf(LoggerInterface::class, $loggers['tracer']);
    }
}
