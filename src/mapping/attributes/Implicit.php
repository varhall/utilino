<?php

namespace Varhall\Utilino\Mapping\Attributes;

use Nette\Schema\Schema;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class Implicit implements IModifier
{
    protected mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function modify(Schema $schema): Schema
    {
        return $schema->default($this->value);
    }
}