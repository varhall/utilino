<?php

namespace Varhall\Utilino\Mapping\Attributes;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\Utils\DateTime;
use Nette\Utils\Validators;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class Date implements IBase
{
    public static array $patterns = [
        '^\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2}(\.\d+)?(([+\-]\d{2}:\d{2})|Z)?)?$'
    ];

    public static function add(string $pattern): void
    {
        static::$patterns[] = $pattern;
    }

    public function schema(): Schema
    {
        return Expect::anyOf(
            Expect::type(\DateTime::class),
            Expect::type('string|int')->transform(fn($x) => DateTime::from($x)),
        )->transform(fn($date) => $date->setTimezone(new \DateTimeZone(date_default_timezone_get())))
         ->transform(fn($date) => $date->format('c'));
    }

    protected function parse(string|int $value): ?DateTime
    {
        if (Validators::isNumericInt($value)) {
            return DateTime::from($value);
        }

        foreach (static::$patterns as $pattern) {
            if (preg_match("/{$pattern}/i", $value)) {
                return DateTime::from($value);
            }
        }

        return null;
    }
}

