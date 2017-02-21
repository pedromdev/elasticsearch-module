<?php

namespace ElasticsearchModule\Service\Loggers;

use ElasticsearchModule\Service\AbstractLogFactory;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class TracerFactory extends AbstractLogFactory
{

    /**
     * {@inheritDoc}
     */
    protected function getKey()
    {
        return 'tracer';
    }
}
