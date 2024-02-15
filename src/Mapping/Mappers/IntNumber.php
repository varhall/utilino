<?php

namespace Varhall\Utilino\Mapping\Mappers;


class IntNumber implements IMapper
{
    public function apply(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }

        if (\Nette\Utils\Validators::isNumericInt($value)) {
            return intval($value);
        }

        return $value;
    }
}
