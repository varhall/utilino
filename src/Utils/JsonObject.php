<?php

namespace Varhall\Utilino\Utils;

use Nette\SmartObject;
use Nette\Utils\Json;
use Varhall\Utilino\ISerializable;

class JsonObject implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable, ISerializable
{
    use SmartObject;

    protected $data = [];

    public $onChange = [];


    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /// Dynamic properties

    public function __get($name)
    {
        return $this[$name];
    }

    public function __set($name, $value)
    {
        return $this[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }


    /// ArrayAccess

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        $value = &$this->data[$offset];

        if (is_array($value)) {
            $json = new static();
            $json->data = &$value;
            $json->onChange = &$this->onChange;
            return $json;
        }

        return $value;
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
        $this->onChange($this, $offset, $value);
    }

    public function offsetUnset($offset): void
    {
        unset ($this->data[$offset]);
    }


    /// Countable

    public function count(): int
    {
        return count($this->data);
    }


    /// IteratorAggregate

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }


    /// JsonSerializable

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /// ISerializable

    public function toArray()
    {
        return $this->data;
    }

    public function toJson()
    {
        return Json::encode($this->toArray());
    }
}