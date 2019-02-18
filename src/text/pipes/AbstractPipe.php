<?php

namespace Varhall\Utilino\Text\Pipes;

abstract class AbstractPipe
{
    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public abstract function apply($text);
}