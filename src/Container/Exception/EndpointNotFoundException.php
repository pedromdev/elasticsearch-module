<?php

namespace ElasticsearchModule\Container\Exception;

use Exception;
use Interop\Container\Exception\NotFoundException;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
class EndpointNotFoundException extends Exception implements NotFoundException
{
}
