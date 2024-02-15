<?php

namespace Varhall\Utilino\Utils;

class Guid
{
    public static function generate()
    {
        if (function_exists('com_create_guid'))
            return strtolower(trim(com_create_guid(), '{}'));

        $guid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        return strtolower($guid);
    }
}