<?php

namespace Varhall\Utilino\Mapping\Attributes;

use Nette\Schema\Schema;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class Assert implements IModifier
{
    protected \Closure $func;
    protected ?string $message;

    public function __construct(array|callable $func, ?string $message = null)
    {
        $this->func = \Closure::fromCallable($func);
        $this->message = $message;
    }

    public function modify(Schema $schema): Schema
    {
        return $schema->assert($this->func, $this->message);
    }
}