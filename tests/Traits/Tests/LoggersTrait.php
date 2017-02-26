<?php

namespace ElasticsearchModuleTest\Traits\Tests;

use PHPUnit_Framework_MockObject_MockObject;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait LoggersTrait
{
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerWithLoggersDependencies(array $config)
    {
        $loggersContainer = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($loggersContainer, 'get', [
            'Config' => $config
        ]);
        return $loggersContainer;
    }
}
