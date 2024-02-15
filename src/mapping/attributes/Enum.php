<?php

namespace Varhall\Utilino\Mapping\Attributes;

use Nette\Schema\Expect;
use Nette\Schema\Schema;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class Enum implements IBase
{
    protected array $values;

    public function __construct(...$values)
    {
        if (count($values) === 1 && is_array($values[0])) {
            $values = $values[0];
        }

        $this->values = $values;
    }

    public function schema(): Schema
    {
        return Expect::anyOf(...$this->values);
    }
}