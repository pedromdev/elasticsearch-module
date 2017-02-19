<?php

namespace ElasticsearchModuleTest\Service;

use ElasticsearchModule\Service\TracerFactory;

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
