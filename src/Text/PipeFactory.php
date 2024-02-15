<?php

namespace Varhall\Utilino\Text;


use Nette\InvalidArgumentException;

class PipeFactory
{
    protected static $pipes = [];

    public static function register($pipe)
    {
        static::$pipes = array_merge(static::$pipes, (array) $pipe);
    }

    public static function create($key, array $options = [])
    {
        if (!isset(static::$pipes[$key]))
            throw new InvalidArgumentException("Unregistered pipe with key {$key}");

        $class = static::$pipes[$key];
        return new $class($options);
    }
}