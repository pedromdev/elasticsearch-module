<?php

namespace ElasticsearchModuleTest\Service\Loggers;

use ElasticsearchModule\Service\Loggers\LoggerFactory;
use ElasticsearchModuleTest\Service\AbstractLogFactoryTest;

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
