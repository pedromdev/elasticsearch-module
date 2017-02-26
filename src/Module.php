<?php

namespace ElasticsearchModule;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class Module implements ConfigProviderInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
