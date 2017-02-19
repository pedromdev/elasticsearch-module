<?php

namespace ElasticsearchModule\Service;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class LoggerFactory extends AbstractLogFactory
{
    /**
     * {@inheritDoc}
     */
    protected function getKey()
    {
        return 'logger';
    }
}
