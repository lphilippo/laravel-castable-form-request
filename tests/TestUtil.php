<?php

namespace Tests;

use ReflectionClass;
use ReflectionProperty;

class TestUtil
{
    /**
     * Gets a property, even though it's protected or private.
     *
     * @param object $instance
     * @param string $propertyName
     *
     * @return mixed
     */
    public static function getProperty($instance, string $propertyName)
    {
        $property = (new ReflectionClass($instance))->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($instance);
    }

    /**
     * Gets a property, even though it's protected or private.
     *
     * @param string $className
     * @param string $propertyName
     *
     * @return mixed
     */
    public static function getStaticProperty(string $className, string $propertyName)
    {
        $property = (new ReflectionProperty($className, $propertyName));
        $property->setAccessible(true);

        return $property->getValue(null);
    }

    /**
     * Invokes a method, even though it's protected or private by providing the instance.
     *
     * @todo Parameter type for $instance should be restricted to "object" as soon as we have upgraded to PHP 7.2
     *
     * @param object $instance
     * @param string $methodName
     * @param array $arguments (optional)
     *
     * @return mixed
     */
    public static function invokeMethod($instance, string $methodName, array $arguments = [])
    {
        $method = (new ReflectionClass($instance))->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($instance, $arguments);
    }

    /**
     * Invokes a static method, even though it's protected or private by providing the class.
     *
     * @param string $className
     * @param string $methodName
     * @param array $arguments (optional)
     *
     * @return mixed
     */
    public static function invokeStaticMethod(string $className, string $methodName, array $arguments = [])
    {
        $method = (new ReflectionClass($className))->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs(null, $arguments);
    }
}
