<?php

namespace ElasticsearchModuleTest\Service;

use ElasticsearchModule\Service\HandlerFactory;
use ElasticsearchModuleTest\Mock\Invokable;
use stdClass;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class HandlerFactoryTest extends AbstractFactoryTest
{
    
    public function testCreateHandlerFromConfiguration()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $factory = new HandlerFactory('default');
        
        $this->assertCreation($factory, $mock);
    }
    
    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @expectedExceptionMessage elasticserach.handler.not-found could not be found
     */
    public function testThrowsExceptionWhenHandlerConfigurationNotExist()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockConfigurationService($mock);
        $factory = new HandlerFactory('not-found');
        
        $factory->createService($mock);
    }
    
    public function testCreateHandlerWithoutParameters()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($mock, 'get', [
            'Config' => [
                'elasticsearch' => [
                    'handler' => [
                        'default' => [],
                    ],
                ],
            ],
        ]);
        $factory = new HandlerFactory('default');
        
        $this->assertCreation($factory, $mock);
    }
    
    public function testCreateInternalFactories()
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($mock, 'get', [
            'Config' => [
                'elasticsearch' => [
                    'handler' => [
                        'default' => [
                            'params' => [
                                'multi_handler' => [
                                    'multi_factory' => function() {},
                                ],
                                'single_handler' => [
                                    'factory' => Invokable::class,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $factory = new HandlerFactory('default');
        
        $this->assertCreation($factory, $mock);
        
    }
    
    public function testCreateInternalFactoryFromServiceLocator()
    {
        $mock = $this->createServiceLocatorMock(['get', 'has']);
        $this->mockMappedReturn($mock, 'get', [
            'Config' => [
                'elasticsearch' => [
                    'handler' => [
                        'default' => [
                            'params' => [
                                'single_handler' => [
                                    'factory' => Invokable::class,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            Invokable::class => new Invokable(),
        ]);
        $this->mockMappedReturn($mock, 'has', [
            Invokable::class => true,
        ]);
        $factory = new HandlerFactory('default');
        
        $this->assertCreation($factory, $mock);
        
    }
    
    /**
     * @param mixed $nonCallable
     * @dataProvider nonCallablesProvider
     */
    public function testRemoveNonCallableInternalFactoryFromParameters($nonCallable)
    {
        $mock = $this->createServiceLocatorMock(['get']);
        $this->mockMappedReturn($mock, 'get', [
            'Config' => [
                'elasticsearch' => [
                    'handler' => [
                        'default' => [
                            'params' => [
                                'single_handler' => [
                                    'factory' => $nonCallable,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            Invokable::class => new Invokable(),
        ]);
        $factory = new HandlerFactory('default');
        
        $this->assertCreation($factory, $mock);
        
    }
    
    private function assertCreation(HandlerFactory $factory, $serviceLocatorMock)
    {
        $handler = $factory->createService($serviceLocatorMock);
        
        $this->assertTrue(is_callable($handler), 'Factory did not return a callable');
    }
    
    public function nonCallablesProvider()
    {
        return [
            [null],
            [1],
            [1.2],
            ['non-callable'],
            [[]],
            [stdClass::class],
        ];
    }
}
