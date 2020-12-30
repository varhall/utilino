<?php

namespace Varhall\Utilino\Utils;


trait Reflection
{
    protected function readPrivateProperty($object, $property)
    {
        $class = new \ReflectionClass(get_class($object));
        $prop = $class->getProperty($property);
        $prop->setAccessible(true);

        return $prop->getValue($object);
    }

    protected function writePrivateProperty($object, $property, $value)
    {
        $class = new \ReflectionClass(get_class($object));
        $prop = $class->getProperty($property);
        $prop->setAccessible(true);

        $prop->setValue($object, $value);
    }
}