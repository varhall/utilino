<?php

namespace Varhall\Utilino\Mapping\Mappers;


class FloatNumber implements IMapper
{
    public function apply(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }

        if (\Nette\Utils\Validators::isNumeric($value)) {
            return floatval($value);
        }

        return $value;
    }
}
