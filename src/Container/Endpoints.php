<?php

namespace ElasticsearchModule\Container;

use Elasticsearch\Endpoints\AbstractEndpoint;
use Elasticsearch\Serializers\SerializerInterface;
use Elasticsearch\Transport;
use ElasticsearchModule\Container\Exception\EndpointNotFoundException;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class Endpoints implements EndpointsInterface
{
    
    const ENDPOINTS_NAMESPACE = "\\Elasticsearch\\Endpoints\\";
    
    /**
     * @var Transport
     */
    private $transport;
    
    /**
     * @var SerializerInterface
     */
    private $serializer;
    
    /**
     * @var AbstractEndpoint[]
     */
    private $instances = [];
    
    /**
     * {@inheritDoc}
     */
    public function __construct(Transport $transport, SerializerInterface $serializer)
    {
        $this->transport = $transport;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke($name)
    {
        if (!$this->has($name)) {
            throw new EndpointNotFoundException("The '$name' endpoint does not exist");
        }
        return $this->get($name);
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        $endpointClass = $this->getEndpointClass($id);
        $endpointId = $this->getEndpointId($endpointClass);
        
        if ($this->hasInstance($endpointId)) {
            return $this->instances[$endpointId];
        }
        
        $instance = $this->getNewInstance($id, $endpointClass);
        $this->instances[$endpointId] = $instance;
        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        $endpointClass = $this->getEndpointClass($id);
        return class_exists($endpointClass);
    }

    /**
     * @param string $endpointClass
     * @return string
     */
    private function getEndpointId($endpointClass)
    {
        $endpointId = strtolower($endpointClass);
        return preg_replace('/[^a-z0-9]/', '', $endpointId);
    }
    
    /**
     * @param string $id
     * @return string
     */
    private function getEndpointClass($id)
    {
        return self::ENDPOINTS_NAMESPACE . $id;
    }
    
    /**
     * @param string $endpointId
     * @return bool
     */
    private function hasInstance($endpointId)
    {
        return isset($this->instances[$endpointId]);
    }
    
    /**
     * @param string $id
     * @param string $endpointClass
     * @return AbstractEndpoint
     */
    private function getNewInstance($id, $endpointClass)
    {
        if (in_array($id, ['Bulk', 'MSearch', 'MPercolate'])) {
            return new $endpointClass($this->transport, $this->serializer);
        } else {
            return new $endpointClass($this->transport);
        }
    }
}
