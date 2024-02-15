<?php

namespace Varhall\Utilino\Mapping\Attributes;


use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Varhall\Utilino\Mapping\Target;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class Structure implements IBase
{
    protected string|object $source;

    public function __construct(string|object $source)
    {
        $this->source = $source;
    }

    public function schema(): Schema
    {
        $reflection = new \ReflectionClass($this->source);
        $properties = [];

        foreach ($reflection->getProperties() as $prop) {
            $prop = new Target($prop);
            $properties[$prop->getName()] = $prop->schema();//->required();
        }

        $object = is_string($this->source) ? new $this->source() : $this->source;

        return Expect::from($object, $properties)
            ->skipDefaults()
            ->before(fn($values) => array_intersect_key($values, array_flip(array_keys($properties))));
    }
}
