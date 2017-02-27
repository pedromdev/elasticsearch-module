<?php

namespace ElasticsearchModule\Service;

use Exception;

/**
 * @author Pedro Alves <pedro.m.develop@gmail.com>
 */
trait InvalidTypeExceptionTrait
{
    /**
     * @param string $name
     * @param string $className
     * @param string $exceptionClass
     * @param mixed $var
     * @return Exception
     */
    private function getException($name, $className, $exceptionClass, $var)
    {
        return new $exceptionClass(sprintf(
            "The %s must be instance of %s, %s given",
            $name,
            $className,
            is_object($var) ? get_class($var) : gettype($var)
        ));
    }
}
