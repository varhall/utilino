<?php

namespace Varhall\Utilino\Utils;


class Reflection
{
    public static function readPrivateProperty($object, $property)
    {
        return self::getPrivateProperty($object, $property)->getValue($object);
    }

    public static function writePrivateProperty($object, $property, $value)
    {
        self::getPrivateProperty($object, $property)->setValue($object, $value);
    }

    private static function getPrivateProperty($object, $property)
    {
        $class = new \ReflectionClass(get_class($object));

        while ($class) {
            if (!$class->hasProperty($property)) {
                $class = $class->getParentClass();
                continue;
            }

            $prop = $class->getProperty($property);
            $prop->setAccessible(true);

            return $prop;
        }

        throw new \ReflectionException("Property {$property} does not exist");
    }

    public static function callPrivateMethod($object, $method, array $args = [])
    {
        $class = new \ReflectionClass(get_class($object));

        while ($class) {
            if (!$class->hasMethod($method)) {
                $class = $class->getParentClass();
                continue;
            }

            $m = $class->getMethod($method);
            $m->setAccessible(true);

            return $m->invokeArgs($object, $args);
        }

        throw new \ReflectionException("Method {$method} does not exist");
    }
}