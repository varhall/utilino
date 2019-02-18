<?php

namespace Varhall\Utilino\Text;


use Nette\InvalidArgumentException;

class Placeholder
{
    public $variable = null;

    public $pipes = [];

    public function __construct($definition)
    {
        $parts = array_filter(array_map('trim', explode('|', $definition)));

        if (empty($parts))
            throw new InvalidArgumentException('Variable name is not defined');

        $this->variable = array_shift($parts);

        foreach ($parts as $pipe) {
            $this->pipes[] = $this->createPipe($pipe);
        }
    }

    public function execute($value)
    {
        // TODO: run pipes
        return $value;
    }

    protected function createPipe($pipe)
    {
        $options = array_filter(array_map('trim', explode(':', $pipe)));
        $key = array_shift($options);

        return PipeFactory::create($key, $options);
    }
}