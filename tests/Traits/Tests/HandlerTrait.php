<?php

namespace ElasticsearchModuleTest\Traits\Tests;

use PHPUnit_Framework_MockObject_MockObject;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait HandlerTrait
{
    
    /**
     * @param array $config
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerWithHandlerDependencies(array $config)
    {
        $handlerContainer = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($handlerContainer, 'get', [
            'Config' => $config
        ]);
        return $handlerContainer;
    }
}
