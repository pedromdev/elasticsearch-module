<?php

namespace ElasticsearchModule\Service;

use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
abstract class AbstractLogFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoggerInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfigurationOrThrowException($serviceLocator);
        $logName = $config['elasticsearch']['loggers'][$this->name][$this->getKey()];
        return $this->getLoggerOrThrowException($serviceLocator, $logName);
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
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     * @throws ServiceNotCreatedException
     */
    private function getConfigurationOrThrowException(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        
        if (!isset($config['elasticsearch']['loggers'][$this->name][$this->getKey()])) {
            throw new ServiceNotCreatedException("elasticserach.loggers.{$this->name} could not be found");
        }
        return $config;
    }
    
    /**
     * Key in log configuration
     * 
     * @return string
     */
    abstract protected function getKey();
}
