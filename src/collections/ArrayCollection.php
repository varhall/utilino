<?php

namespace Varhall\Utilino\Collections;

use Traversable;
use Varhall\Utilino\ISerializable;

class ArrayCollection implements ICollection, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $data = [];

    protected $_searchFunc = null;

    /// Creation methods

    public function __construct(...$values)
    {
        if (empty($values))
            $values = [[]];

        $this->data = call_user_func_array('array_merge', array_map(function($item) {
            if ($item instanceof ICollection)
                return $item->asArray();

            if ($item instanceof ISerializable)
                return $item->toArray();

            return (array)$item;
        }, $values));
    }

    public static function create(...$values)
    {
        return new static(...$values);
    }

    public static function range($first, $last, $step = 1)
    {
        $array = [];

        for ($i = $first; $i <= $last; $i += $step) {
            $array[] = $i;
        }

        return new static($array);
    }



    /// Array Methods

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->data;
    }



    public function searchFunc(callable $func)
    {
        $this->_searchFunc = $func;

        return $this;
    }


    /// ICollection interface

    public function count()
    {
        return count($this->data);
    }

    public function limit(?int $limit, ?int $offset = null)
    {
        return new static(array_slice($this->data, $offset !== null ? $offset : 0, $limit));
    }

    public function each(callable $func)
    {
        $index = 0;

        foreach ($this->data as $key => $value) {
            if (call_user_func($func, $value, $key, $index++) === false)
                break;
        }

        return $this;
    }

    public function every(callable $func)
    {
        foreach ($this->data as $item) {
            if (call_user_func($func, $item) === false)
                return false;
        }

        return true;
    }
    
    public function any(callable $func)
    {
        foreach ($this->data as $item) {
            if (call_user_func($func, $item) === true)
                return true;
        }
        
        return false;
    }

    public function filter(callable $func)
    {
        return new static(array_filter($this->data, function($item, $key) use ($func) {
            return call_user_func_array($func, [$item, $key]);
        }, ARRAY_FILTER_USE_BOTH));
    }

    public function filterKeys($keys)
    {
        return new static(array_filter($this->data, function($key) use ($keys) {
            if (is_array($keys))
                return in_array($key, $keys);

            else if (is_callable($keys))
                return call_user_func($keys, $key);

            return true;
        }, ARRAY_FILTER_USE_KEY));
    }

    public function first(callable $func = null)
    {
        $collection = !!$func ? $this->filter($func) : $this;

        if (!$collection->count())
            return null;

        return $collection[0];
    }

    public function flatten()
    {
        return $this->reduce(function($carry, $item) {
            return $carry->merge($item);
        }, ArrayCollection::create());
    }

    public function isEmpty()
    {
        return $this->count() === 0;
    }

    public function keys()
    {
        return new static(array_keys($this->data));
    }

    public function last(callable $func = null)
    {
        $collection = !!$func ? $this->filter($func) : $this;

        if (!$collection->count())
            return null;

        return $collection[$collection->count() - 1];
    }

    public function map(callable $func)
    {
        return new static(array_map($func, $this->data));
    }

    public function merge($collection)
    {
        $array = ($collection instanceof ICollection) ? $collection->asArray() : $collection;

        return new static(array_merge($this->asArray(), $array));
    }

    public function pad($size, $value)
    {
        return new static(array_pad($this->data, $size, $value));
    }

    public function pipe(callable $func)
    {
        return call_user_func_array($func, [ $this ]);
    }

    public function pop()
    {
        return array_pop($this->data);
    }

    public function prepend($value)
    {
        array_unshift($this->data, $value);

        return $this;
    }

    public function push($value)
    {
        $this->data[] = $value;

        return $this;
    }

    public function reduce(callable $func, $initial = null)
    {
        return array_reduce($this->data, $func, $initial);
    }

    public function reverse()
    {
        return new static(array_reverse($this->asArray()));
    }

    public function search($value, callable $func = null)
    {
        if ($func === null && $this->_searchFunc !== null)
            $func = $this->_searchFunc;

        return new static($this->filter(function($item) use($func, $value) {
            return !!$func ? call_user_func($func, $item, $value) : true;
        }));
    }

    public function shift()
    {
        return array_shift($this->data);
    }

    public function sort(callable $func)
    {
        usort($this->data, $func);
        return $this;
    }

    public function values()
    {
        return new static(array_values($this->data));
    }

    public function asArray()
    {
        return $this->data;
    }

    public function toArray()
    {
        return $this->map(function($item) {
            if (is_array($item) || is_scalar($item) || is_null($item))
                return $item;

            else if ($item instanceof \Varhall\Utilino\ISerializable)
                return $item->toArray();

            else if ($item instanceof \Nette\Database\Table\ActiveRow)
                return $item->toArray();

            else if (is_object($item))
                return json_decode(json_encode($item), true);

            return null;
        })->data;
    }

    public function toJson()
    {
        return json_encode($this);
    }
}