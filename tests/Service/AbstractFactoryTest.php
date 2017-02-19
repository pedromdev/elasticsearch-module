<?php

namespace ElasticsearchModuleTest\Service;

use PHPUnit_Framework_MockObject_MockObject;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
abstract class AbstractFactoryTest extends AbstractTest
{
    
    /**
     * @param array $methods
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createServiceLocatorMock(array $methods)
    {
        return $this->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }
    
    protected function mockConfigurationService(PHPUnit_Framework_MockObject_MockObject $mock)
    {
        $mock->expects($this->any())
            ->method('get')
            ->with($this->equalTo('Config'))
            ->willReturn($this->getConfig());
    }
    
    protected function mockMappedReturn(PHPUnit_Framework_MockObject_MockObject $mock, $method, array $map)
    {
        $mock->expects($this->any())
            ->method($method)
            ->willReturnCallback(function($name) use ($map) {
                return isset($map[$name]) ? $map[$name] : null;
            });
    }
}
