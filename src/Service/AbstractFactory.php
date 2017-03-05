<?php

namespace ElasticsearchModule\Service;

use ArrayObject;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject as ZendArrayObject;

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
     * @param ContainerInterface $container
     * @param array|ArrayObject|ZendArrayObject $config
     */
    abstract protected function create(ContainerInterface $container, $config);
    
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     * @throws ServiceNotCreatedException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, sprintf('elasticsearch.%s.%s', $this->getServiceType(), $this->name));
    }
    
    /**
     * @param ContainerInterface $container
     * @return mixed
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $serviceType = $this->getServiceType();
        
        if (!isset($config['elasticsearch'][$serviceType][$this->name])) {
            throw new ServiceNotCreatedException("$requestedName could not be found");
        }
        
        return $this->create($container, $config['elasticsearch'][$serviceType][$this->name]);
    }
    
    /**
     * @param ContainerInterface $container
     * @param string $serviceOrClass
     * @return mixed
     */
    protected function getServiceOrClassObject(ContainerInterface $container, $serviceOrClass)
    {
        if ($container->has($serviceOrClass)) {
            return $container->get($serviceOrClass);
        } else if (class_exists($serviceOrClass)) {
            return new $serviceOrClass();
        }
        return null;
    }
    
    /**
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }
}
