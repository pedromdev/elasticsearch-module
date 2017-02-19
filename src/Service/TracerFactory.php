<?php

namespace ElasticsearchModule\Service;

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
