<?php

namespace Varhall\Utilino\Utils;

use Nette\Utils\DateTime;
use Varhall\Utilino\Collections\ArrayCollection;
use Varhall\Utilino\ISerializable;

class XmlElement implements \IteratorAggregate, ISerializable
{
    /** @var \SimpleXMLElement */
    public $xml;

    public function __construct($xml)
    {
        if (is_string($xml))
            $xml = simplexml_load_string($xml);

        $this->xml = $xml;
    }

    public function __get($name)
    {
        if ($this->xml && $this->xml->$name && $this->xml->$name->count() > 1) {
            return new XmlCollection($this->xml->$name);
        }

        return new static($this->xml ? $this->xml->$name : null);
    }

    public function getIterator(): \Traversable
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

    public function collection()
    {
        return new XmlCollection($this->xml->count ? [ $this->xml ] : []);
    }

    public function toXml()
    {
        return $this->xml->asXML();
    }

    public function toArray()
    {
        $array = json_decode(json_encode($this->xml), true);
        return $this->lowerKeys($array);
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    protected function lowerKeys($arr, $case = CASE_LOWER)
    {
        return array_map(function($item)use($case){
            if (is_array($item))
                $item = $this->lowerKeys($item, $case);
            return $item;
        }, array_change_key_case($arr, $case));
    }
}
