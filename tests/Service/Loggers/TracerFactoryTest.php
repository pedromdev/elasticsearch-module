<?php

namespace ElasticsearchModuleTest\Service\Loggers;

use ElasticsearchModule\Service\Loggers\TracerFactory;
use ElasticsearchModuleTest\Service\AbstractLogFactoryTest;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class TracerFactoryTest extends AbstractLogFactoryTest
{
    
    /**
     * {@inheritDoc}
     */
    protected function getLogFactory($name)
    {
        return new TracerFactory($name);
    }
}
