<?php

namespace ElasticsearchModuleTest;

use ElasticsearchModule\Module;
use PHPUnit_Framework_TestCase;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ModuleTest extends PHPUnit_Framework_TestCase
{
    
    public function testModuleHasConfiguration()
    {
        $module = new Module();
        
        $this->assertTrue(method_exists($module, 'getConfig'), "The 'getConfig' method does not exist");
        $this->assertTrue(is_array($module->getConfig()), "The 'getConfig' method does not return an array");
    }
}
