<?php

namespace ElasticsearchModule\Service;

use ArrayObject;
use Elasticsearch\ConnectionPool\AbstractConnectionPool;
use Elasticsearch\Connections\ConnectionFactoryInterface;
use Elasticsearch\Connections\ConnectionInterface;
use Zend\ServiceManager\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject as ZendArrayObject;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class ConnectionPoolFactory extends AbstractFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $className = $config['class'];
        
        if (!in_array(AbstractConnectionPool::class, class_parents($className))) {
            throw new ServiceNotCreatedException(sprintf(
                "'%s' does not implement %s",
                $className,
                AbstractConnectionPool::class
            ));
        }
        $selector = $this->getServiceOrClassObject($serviceLocator, $config['selector']);
        $connectionFactory = $this->getServiceOrClassObject($serviceLocator, $config['connection_factory']);
        $parameters = isset($config['parameters']) ? $config['parameters'] : [];
        $connections = $this->getConnectionsFromConfiguration($config, $connectionFactory);
        return new $className(
            $connections,
            $selector,
            $connectionFactory,
            $parameters
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'connection_pool';
    }
    
    /**
     * @param array|ArrayObject|ZendArrayObject $config
     * @param ConnectionFactoryInterface $connectionFactory
     * @return ConnectionInterface[]
     * @throws ServiceNotCreatedException
     */
    private function getConnectionsFromConfiguration($config, ConnectionFactoryInterface $connectionFactory)
    {
        $connections = [];
        
        foreach ($config['hosts'] as $host) {
            if (!is_string($host) && !is_array($host)) {
                throw new ServiceNotCreatedException("Could not parse host: " . print_r($host, true));
            }
            
            if (is_string($host)) {
                $host = $this->extractURIParts($host);
            }
            $host = $this->normalizeExtendedHost($host);
            $connections[] = $connectionFactory->create($host);
        }
        return $connections;
    }

    /**
     * @param $host
     * @return array
     */
    private function normalizeExtendedHost($host) {
        if (isset($host['host']) === false) {
            throw new ServiceNotCreatedException(
                "Required 'host' was not defined in extended format: " . print_r($host, true)
            );
        }
        return array_merge(['scheme' => 'http', 'port' => 9200], $host);
    }

    /**
     * @param string $host
     *
     * @throws InvalidArgumentException
     * @return array
     */
    private function extractURIParts($host)
    {
        $host = filter_var($host, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) ? $host : 'http://' . $host;
        $parts = parse_url($host);

        if ($parts === false) {
            throw new InvalidArgumentException("Could not parse URI");
        }
        return array_merge(['port' => 9200], $parts);
    }

}
