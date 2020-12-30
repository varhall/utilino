<?php

namespace Varhall\Utilino\Utils;


trait Reflection
{
    private function readPrivateProperty($object, $property)
    {
        return $this->getPrivateProperty($object, $property)->getValue($object);
    }

    private function writePrivateProperty($object, $property, $value)
    {
        $this->getPrivateProperty($object, $property)->setValue($object, $value);
    }

    private function getPrivateProperty($object, $property)
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

    private function callPrivateMethod($object, $method, array $args = [])
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