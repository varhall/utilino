<?php

namespace Varhall\Utilino\Mapping\Mappers;


class Boolean implements IMapper
{
    public function apply(mixed $value): mixed
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
