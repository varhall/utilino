<?php

namespace Varhall\Utilino\Collections;


interface ICollection extends \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable, ISearchable, ISerializable
{
    public function all();

    public function count();

    public function each(callable $func);

    public function every(callable $func);

    public function filter(callable $func);

    public function first(callable $func = NULL);

    public function isEmpty();

    public function keys();

    public function last(callable $func = NULL);

    public function map(callable $func);

    public function merge($collection);

    public function pad($size, $value);

    public function pipe(callable $func);

    public function pop();

    public function prepend($value);

    public function push($value);

    public function reduce(callable $func, $initial = NULL);

    public function shift();

    public function sort(callable $func);

    public function values();
}