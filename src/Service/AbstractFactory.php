<?php

namespace ElasticsearchModule\Service;

use ArrayObject;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
abstract class AbstractFactory implements FactoryInterface
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
     * @return string
     */
    abstract public function getServiceType();
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|ArrayObject $config
     */
    abstract public function create(ServiceLocatorInterface $serviceLocator, $config);
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     * @throws ServiceNotCreatedException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $serviceType = $this->getServiceType();
        
        if (!isset($config['elasticsearch'][$serviceType][$this->name])) {
            throw new ServiceNotCreatedException("elasticserach.$serviceType.{$this->name} could not be found");
        }
        
        return $this->create($serviceLocator, $config['elasticsearch'][$serviceType][$this->name]);
    }
}
