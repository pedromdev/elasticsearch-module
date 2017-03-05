<?php

namespace ElasticsearchModule\Service;

use Elasticsearch\ConnectionPool\AbstractConnectionPool;
use Elasticsearch\Transport;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class TransportFactory extends AbstractFactory
{
    use InvalidTypeExceptionTrait;
 
    /**
     * {@inheritDoc}
     */
    protected function create(ContainerInterface $container, $config)
    {
        $retries = isset($config['retries']) ? $config['retries'] : 2;
        $sniffOnStart = isset($config['sniff_on_start']) ? $config['sniff_on_start'] : false;
        $connectionPool = $container->get($config['connection_pool']);
        
        if (!$connectionPool instanceof AbstractConnectionPool) {
            throw $this->getException(
                'connection pool',
                AbstractConnectionPool::class,
                ServiceNotCreatedException::class,
                $connectionPool
            );
        }
        $loggers = $container->get($config['loggers']);
        
        return new Transport($retries, $sniffOnStart, $connectionPool, $loggers['logger']);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'transport';
    }
}
