<?php

namespace ElasticsearchModule\Service;

use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
abstract class AbstractLogFactory extends AbstractFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $logName = $config[$this->getKey()];
        return $this->getLoggerOrThrowException($serviceLocator, $logName);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'loggers';
    }
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $logName
     * @return LoggerInterface
     * @throws ServiceNotCreatedException
     */
    private function getLoggerOrThrowException(ServiceLocatorInterface $serviceLocator, $logName)
    {
        if ($serviceLocator->has($logName)) {
            return $serviceLocator->get($logName);
        } else if (class_exists($logName)) {
            return new $logName();
        }
        
        throw new ServiceNotCreatedException("There is no '$logName' log");
    }
    
    /**
     * Key in log configuration
     * 
     * @return string
     */
    abstract protected function getKey();
}
