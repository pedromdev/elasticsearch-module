<?php

namespace ElasticsearchModule\Service;

use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class LoggerFactory implements FactoryInterface
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
     * @return \Psr\Log\LoggerInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfigurationOrThrowException($serviceLocator);
        $loggerName = $config['elasticsearch']['log'][$this->name]['logger'];
        return $this->getLoggerOrThrowException($serviceLocator, $loggerName);
    }
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $loggerName
     * @return \Psr\Log\LoggerInterface
     * @throws ServiceNotCreatedException
     */
    private function getLoggerOrThrowException(ServiceLocatorInterface $serviceLocator, $loggerName)
    {
        if ($serviceLocator->has($loggerName)) {
            return $serviceLocator->get($loggerName);
        } else if (class_exists($loggerName)) {
            return new $loggerName();
        }
        
        throw new ServiceNotCreatedException("There is no '$loggerName' logger");
    }
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     * @throws ServiceNotCreatedException
     */
    private function getConfigurationOrThrowException(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        
        if (!isset($config['elasticsearch']['log'][$this->name]['logger'])) {
            throw new ServiceNotCreatedException("elasticserach.log.{$this->name} could not be found");
        }
        return $config;
    }
}
