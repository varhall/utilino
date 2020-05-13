<?php

namespace Varhall\Utilino\Utils;

class XmlElement
{
    private $xml = null;

    public function __construct($xml)
    {
        $this->xml = $xml;
    }

    public function __get($name)
    {
        return new static($this->xml ? $this->xml->$name : null);
    }

    public function value()
    {
        return $this->xml ? trim($this->xml->__toString()) : '';
    }
}