<?php

namespace ElasticsearchModule\Service;

use ArrayObject;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class LoggersFactory extends AbstractFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function create(ServiceLocatorInterface $serviceLocator, $config)
    {
        $factories = $config['factories'];
        $loggers = [];
        
        foreach ($factories as $name => $factory) {
            if (in_array(AbstractFactory::class, class_parents($factory))) {
                $factory = new $factory($this->getName());
                $loggers[$name] = $factory->create($serviceLocator, $config);
            }
        }
        return new ArrayObject($loggers);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceType()
    {
        return 'loggers';
    }

}
