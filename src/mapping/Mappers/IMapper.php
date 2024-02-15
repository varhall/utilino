<?php

namespace Varhall\Utilino\Mapping\Mappers;

interface IMapper
{
    public function apply(mixed $value): mixed;
}
