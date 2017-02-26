<?php

namespace ElasticsearchModuleTest\Service;

use Elasticsearch\Transport;
use ElasticsearchModule\Service\TransportFactory;
use ElasticsearchModuleTest\Traits\Tests\TransportTrait;

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
}
