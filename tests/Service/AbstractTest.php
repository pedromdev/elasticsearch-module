<?php

namespace ElasticsearchModuleTest\Service;

use PHPUnit_Framework_TestCase;

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
}
