<?php

namespace ElasticsearchModule\Service;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
abstract class AbstractLogFactory extends AbstractFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function create(ContainerInterface $container, $config)
    {
        $logName = $config[$this->getKey()];
        return $this->getLoggerOrThrowException($container, $logName);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'loggers';
    }
    
    /**
     * @param ContainerInterface $container
     * @param string $logName
     * @return LoggerInterface
     * @throws ServiceNotCreatedException
     */
    private function getLoggerOrThrowException(ContainerInterface $container, $logName)
    {
        $logger = $this->getServiceOrClassObject($container, $logName);
        
        if (!$logger instanceof LoggerInterface) {
            throw new ServiceNotCreatedException("There is no '$logName' log");
        }
        return $logger;
    }
    
    /**
     * Key in log configuration
     * 
     * @return string
     */
    abstract protected function getKey();
}
