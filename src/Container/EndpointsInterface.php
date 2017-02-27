<?php

namespace ElasticsearchModule\Container;

use Elasticsearch\Endpoints\AbstractEndpoint;
use Elasticsearch\Serializers\SerializerInterface;
use Elasticsearch\Transport;
use ElasticsearchModule\Container\Exception\EndpointNotFoundException;
use ElasticsearchModule\Container\Exception\InvalidEndpointException;
use Interop\Container\ContainerInterface;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
interface EndpointsInterface extends ContainerInterface
{
    
    /**
     * @param Transport $transport
     * @param SerializerInterface $serializer
     */
    public function __construct(Transport $transport, SerializerInterface $serializer);
    
    /**
     * @param string $name
     * @return AbstractEndpoint
     * @throws EndpointNotFoundException
     * @throws InvalidEndpointException
     */
    public function __invoke($name);
}
