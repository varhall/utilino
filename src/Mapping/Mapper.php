<?php

namespace Varhall\Utilino\Mapping;

use Nette\Schema\Processor;
use Varhall\Utilino\Mapping\Attributes\Structure;

class Mapper
{
    public static function map(array $data, string|object $type): mixed
    {
        $schema = (new Structure($type))->schema();
        $processor = new Processor();

        return $processor->process($schema, $data);
    }
}