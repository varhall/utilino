<?php

namespace Varhall\Utilino\Collections;

use Traversable;

class ArrayCollection implements ICollection, IteratorAggregate
{
    /**
     * @var array
     */
    protected $data = [];

    /// Creation methods

    public function __construct(...$values)
    {
        $this->data = call_user_func_array('array_merge', array_map(function($item) {
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




    /// ICollection interface

    public function count()
    {
        return count($this->data);
    }

    public function each(callable $func)
    {
        $index = 0;

        foreach ($this->data as $key => $value) {
            if (call_user_func($func, $value, $key, $index++) === FALSE)
                break;
        }

        return $this;
    }

    public function every(callable $func)
    {
        return $this->reduce(function($carry, $item) use ($func) {
            return $carry && call_user_func($func, $item);
        }, TRUE);
    }

    public function filter(callable $func)
    {
        return new static(array_filter($this->data, function($item, $key) use ($func) {
            return call_user_func_array($func, $item, $key);
        }), ARRAY_FILTER_USE_BOTH);
    }

    public function first(callable $func = NULL)
    {
        $collection = !!$func ? $this->filter($func) : $this;

        if (!$collection->count())
            return NULL;

        return $collection[0];
    }

    public function isEmpty()
    {
        return $this->count() === 0;
    }

    public function keys()
    {
        return new static(array_keys($this->data));
    }

    public function last(callable $func = NULL)
    {
        $collection = !!$func ? $this->filter($func) : $this;

        if (!$collection->count())
            return NULL;

        return $collection[$collection->count() - 1];
    }

    public function map(callable $func)
    {
        return new static(array_map($func, $this->data));
    }

    public function merge($collection)
    {
        $array = ($collection instanceof ICollection) ? $collection->toArray() : $collection;

        return new static(array_merge($this->data, $array));
    }

    public function pad($size, $value)
    {
        return new static(array_pad($this->data, $size, $value));
    }

    public function pipe(callable $func)
    {
        return call_user_func_array($func, $this);
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

    public function reduce(callable $func, $initial = NULL)
    {
        return array_reduce($this->data, $func);
    }

    public function search($value, callable $func = NULL)
    {
        return new static($this->filter(function($item) use($func, $value) {
            return (!!$func ? call_user_func($func, $item) : $item) == $value;
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

    public function toArray()
    {
        return $this->data;
    }

    public function toJson()
    {
        return json_encode($this);
    }
}