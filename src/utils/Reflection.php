<?php

namespace Varhall\Utilino\Utils;


trait Reflection
{
    protected function readPrivateProperty($object, $property)
    {
        return $this->getPrivateProperty($object, $property)->getValue($object);
    }

    protected function writePrivateProperty($object, $property, $value)
    {
        $this->getPrivateProperty($object, $property)->setValue($object, $value);
    }

    protected function getPrivateProperty($object, $property)
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
}