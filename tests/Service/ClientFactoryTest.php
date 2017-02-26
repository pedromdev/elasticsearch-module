<?php

namespace ElasticsearchModuleTest\Service;

use Elasticsearch\Client;
use ElasticsearchModule\Service\ClientFactory;
use ElasticsearchModuleTest\Traits\Tests\ClientTrait;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ClientFactoryTest extends AbstractFactoryTest
{
    use ClientTrait;
    
    public function testCreateClientFromConfiguration()
    {
        $clientContainer = $this->getContainerWithClientDependencies($this->getConfig());
        $factory = new ClientFactory('default');
        
        $client = $factory->createService($clientContainer);
        
        $this->assertInstanceOf(Client::class, $client);
    }
}
