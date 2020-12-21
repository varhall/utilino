<?php

namespace Varhall\Utilino\Utils;

use Nette\Utils\DateTime;

class XmlElement implements \IteratorAggregate
{
    public $xml = null;

    public function __construct($xml)
    {
        $this->xml = $xml;
    }

    public function __get($name)
    {
        if ($this->xml && $this->xml->$name && $this->xml->$name->count() > 1) {
            $result = [];

            foreach ($this->xml->$name as $item) {
                $result[] = new static($item);
            }

            return $result;
        }

        return new static($this->xml ? $this->xml->$name : null);
    }

    public function getIterator()
    {
        $array = iterator_to_array($this->xml);

        return new \ArrayIterator(array_map(function($item) {
            return new static($item);
        }, $array));
    }

    public function value()
    {
        return $this->xml ? trim($this->xml->__toString()) : '';
    }

    public function number()
    {
        return is_numeric($this->value()) ? $this->value() + 0 : $this->value();
    }

    public function date()
    {
        return !empty($this->value()) ? new DateTime($this->value()) : null;
    }
}
