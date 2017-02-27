<?php

namespace ElasticsearchModuleTest\Service;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @return  array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
    
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
    
    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mock
     */
    protected function mockConfigurationService(PHPUnit_Framework_MockObject_MockObject $mock)
    {
        $mock->expects($this->any())
            ->method('get')
            ->with($this->equalTo('Config'))
            ->willReturn($this->getConfig());
    }
    
    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mock
     * @param string $method
     * @param array $map
     */
    protected function mockMappedReturn(PHPUnit_Framework_MockObject_MockObject $mock, $method, array $map)
    {
        $mock->expects($this->any())
            ->method($method)
            ->willReturnCallback(function($name) use ($map) {
                return isset($map[$name]) ? $map[$name] : null;
            });
    }
}
