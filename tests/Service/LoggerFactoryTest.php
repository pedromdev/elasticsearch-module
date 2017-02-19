<?php

namespace ElasticsearchModuleTest\Service;

use ElasticsearchModule\Service\LoggerFactory;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class LoggerFactoryTest extends AbstractLogFactoryTest
{
    /**
     * {@inheritDoc}
     */
    protected function getLogFactory($name)
    {
        return new LoggerFactory($name);
    }
}
