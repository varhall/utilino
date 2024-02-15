<?php

namespace Varhall\Utilino\Mapping\Attributes;

use Nette\Schema\Schema;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Required implements IModifier
{
    public function modify(Schema $schema): Schema
    {
        return $schema->required();
    }
}