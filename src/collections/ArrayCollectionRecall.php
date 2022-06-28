<?php

namespace Varhall\Utilino\Collections;


trait ArrayCollectionRecall
{
    /**
     * Returns true if every item in collection matches given function
     *
     * @param callable $func args: $item
     * @return boolean
     */
    public function every(callable $func)
    {
        return $this->toArrayCollection()->every($func);
    }

    /**
     * Return true if any of items in collection matches given function
     *
     * @param callable $func
     * @return boolean
     */
    public function any(callable $func)
    {
        return $this->toArrayCollection()->any($func);
    }

    /**
     * Returns new collection where each item matches given function
     *
     * @param callable $func args: $item
     * @return ICollection
     */
    public function filter(callable $func)
    {
        return $this->toArrayCollection()->filter($func);
    }

    /**
     * Returns new colletion where each key is in given array or matches given function
     *
     * @param $keys array|callable
     * @return ICollection
     */
    public function filterKeys($keys)
    {
        return $this->toArrayCollection()->filterKeys($keys);
    }

    /**
     * @return Reduces level of collection
     */
    public function flatten()
    {
        return $this->toArrayCollection()->flatten();
    }

    /**
     * Runs the function for chunks of given size
     *
     * @param int $size Chunk size
     * @param callable $func Function
     * @return ICollection
     */
    public function chunk(int $size, callable $func)
    {
        return $this->toArrayCollection()->chunk($size, $func);
    }

    /**
     * Collection of keys
     *
     * @return ICollection
     */
    public function keys()
    {
        return new ArrayCollection(array_keys($this->fetchPairs()));
    }

    /**
     * Returns last item which matches function if given
     *
     * @param callable|null $func args: $item
     * @return mixed
     */
    public function last(callable $func = null)
    {
        return $this->toArrayCollection()->last($func);
    }

    /**
     * Transforms each item using given function
     *
     * @param callable $func args: $item
     * @return ICollection
     */
    public function map(callable $func)
    {
        return $this->toArrayCollection()->map($func);
    }

    /**
     * Fills current collection to required length with default value
     *
     * @param $size
     * @param $value
     * @return mixed
     */
    public function pad($size, $value)
    {
        return $this->toArrayCollection()->pad($size, $value);
    }

    /**
     * Removes and returns last item from collection
     *
     * @return mixed
     */
    public function pop()
    {
        return $this->toArrayCollection()->pop();
    }

    /**
     * Inserts value at the begining of the collection
     *
     * @param $value
     * @return mixed
     */
    public function prepend($value)
    {
        return $this->toArrayCollection()->prepend($value);
    }

    /**
     * Adds the value in the and of the collection
     *
     * @param $value
     * @return mixed
     */
    public function push($value)
    {
        return $this->toArrayCollection()->push($value);
    }

    /**
     * Accumulates value through all elements from the start to end
     *
     * @param callable $func
     * @param mixed|null $initial
     * @return mixed
     */
    public function reduce(callable $func, $initial = null)
    {
        return $this->toArrayCollection()->reduce($func, $initial);
    }

    public function reverse()
    {
        return $this->toArrayCollection()->reverse();
    }

    /**
     * Removes first value from the collection
     *
     * @return mixed
     */
    public function shift()
    {
        return $this->toArrayCollection()->shift();
    }

    /**
     * Sorts collection using given compare function
     *
     * @param callable $func args: $a, $b
     * @return ICollection
     */
    public function sort(callable $func)
    {
        return $this->toArrayCollection()->sort($func);
    }

    /**
     * Returns collection of values
     *
     * @return ICollection
     */
    public function values()
    {
        return new ArrayCollection(array_values($this->fetchPairs()));
    }

    protected function toArrayCollection()
    {
        return new ArrayCollection($this->asArray());
    }
}