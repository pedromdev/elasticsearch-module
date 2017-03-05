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
     * @param string $classNameOrType
     * @param string $exceptionClass
     * @param mixed $var
     * @return Exception
     */
    private function getException($name, $classNameOrType, $exceptionClass, $var)
    {
        $message = (class_exists($classNameOrType) || interface_exists($classNameOrType)) ?
            "instance of %s" : "%s";
        return new $exceptionClass(sprintf(
            "The %s must be $message, %s given",
            $name,
            $classNameOrType,
            is_object($var) ? get_class($var) : gettype($var)
        ));
    }
}
