<?php

namespace Varhall\Utilino\Mapping\Attributes;

use Nette\Schema\Schema;

interface IModifier
{
    public function modify(Schema $schema): Schema;
}