<?php

namespace Varhall\Utilino\Utils;


class Path
{
    public static function combine(...$parts)
    {
        $prefix = !empty($parts) && preg_match('#^[/\\\]#', $parts[0]) ? $parts[0][0] : '';

        return $prefix . implode(DIRECTORY_SEPARATOR, array_map(function($part) {
            return trim($part, " \t\n\r\0\x0B\\/");
        }, $parts));
    }
}