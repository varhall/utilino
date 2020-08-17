<?php

namespace Varhall\Utilino\Utils;


class Path
{
    public static function combine(...$parts)
    {
        return implode(DIRECTORY_SEPARATOR, array_map(function($part) {
            return trim($part, " \t\n\r\0\x0B\\/");
        }, $parts));
    }
}