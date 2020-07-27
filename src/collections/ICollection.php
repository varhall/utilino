<?php

namespace Varhall\Utilino\Collections;

use Varhall\Utilino\ISerializable;

interface ICollection extends \ArrayAccess, \Countable, \JsonSerializable, ISearchable, IPaginable, ISerializable
{
    /**
     * Creates shallow conversion to array. All nested structures remain unchanged
     *
     * @return array
     */
    public function asArray();

    /**
     * Size of collection
     *
     * @return int
     */
    public function count();

    /** Slices collection to size, optionally from offset */
    public function limit(?int $limit, ?int $offset = null);

    /**
     * Executes function for each item in collection
     *
     * @param callable $func args: $item
     * @return ICollection
     */
    public function each(callable $func);

    /**
     * Returns true if every item in collection matches given function
     *
     * @param callable $func args: $item
     * @return boolean
     */
    public function every(callable $func);

    /**
     * Return true if any of items in collection matches given function
     *
     * @param callable $func
     * @return boolean
     */
    public function any(callable $func);

    /**
     * Returns new collection where each item matches given function
     *
     * @param callable $func args: $item
     * @return ICollection
     */
    public function filter(callable $func);

    /**
     * Returns new colletion where each key is in given array or matches given function
     *
     * @param $keys array|callable
     * @return ICollection
     */
    public function filterKeys($keys);

    /**
     * Returns first item which matches function if given
     *
     * @param callable|NULL $func args: $item
     * @return mixed
     */
    public function first(callable $func = NULL);

    /**
     * @return Reduces level of collection
     */
    public function flatten();

    /**
     * True if collection is empty
     *
     * @return boolean
     */
    public function isEmpty();

    /**
     * Collection of keys
     *
     * @return ICollection
     */
    public function keys();

    /**
     * Returns last item which matches function if given
     *
     * @param callable|NULL $func args: $item
     * @return mixed
     */
    public function last(callable $func = NULL);

    /**
     * Transforms each item using given function
     *
     * @param callable $func args: $item
     * @return ICollection
     */
    public function map(callable $func);

    /**
     * Merge array or collection with current collection
     *
     * @param ICollection|array $collection
     * @return ICollection
     */
    public function merge($collection);

    /**
     * Fills current collection to required length with default value
     *
     * @param $size
     * @param $value
     * @return mixed
     */
    public function pad($size, $value);

    /**
     * Passes current collection into given function and returns the function value
     *
     * @param callable $func args: $this
     * @return mixed
     */
    public function pipe(callable $func);

    /**
     * Removes and returns last item from collection
     *
     * @return mixed
     */
    public function pop();

    /**
     * Inserts value at the begining of the collection
     *
     * @param $value
     * @return mixed
     */
    public function prepend($value);

    /**
     * Adds the value in the and of the collection
     *
     * @param $value
     * @return mixed
     */
    public function push($value);

    /**
     * Accumulates value through all elements from the start to end
     *
     * @param callable $func
     * @param mixed|null $initial
     * @return mixed
     */
    public function reduce(callable $func, $initial = NULL);

    /**
     * Reverse order of collection
     *
     * @return ICollection
     */
    public function reverse();

    /**
     * Removes first value from the collection
     *
     * @return mixed
     */
    public function shift();

    /**
     * Sorts collection using given compare function
     *
     * @param callable $func args: $a, $b
     * @return ICollection
     */
    public function sort(callable $func);

    /**
     * Returns collection of values
     *
     * @return ICollection
     */
    public function values();
}