<?php

namespace Tests\Utilino\Collections;

use Nette\Database\Table\ActiveRow;
use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Collections\ArrayCollection;
use Varhall\Utilino\ISerializable;

require __DIR__ . '/../../bootstrap.php';

class ArrayCollectionTest extends TestCase
{
    public function testCreateValues()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);
        Assert::equal([ 1, 2, 3, 4, 5 ], $collection->toArray());
    }

    public function testCreateArray()
    {
        $collection = ArrayCollection::create([ 1, 2, 3, 4, 5 ]);
        Assert::equal([ 1, 2, 3, 4, 5 ], $collection->toArray());
    }

    public function testCreateCollection()
    {
        $source = ArrayCollection::create(1, 2, 3, 4, 5);
        $collection = ArrayCollection::create($source);
        Assert::equal([ 1, 2, 3, 4, 5 ], $collection->toArray());
    }

    public function testRange()
    {
        $collection = ArrayCollection::range(1, 5);
        Assert::equal([ 1, 2, 3, 4, 5 ], $collection->toArray());
    }

    public function testForEach()
    {
        $source = ArrayCollection::create(1, 2, 3, 4, 5);
        $expected = [ 1, 2, 3, 4, 5 ];

        $i = 0;
        foreach ($source as $item) {
            Assert::equal($expected[$i++], $item);
        }
    }

    public function testOffsetExists()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);
        Assert::true(isset($collection[1]));
        Assert::false(isset($collection[5]));
    }

    public function testOffsetGet()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);
        Assert::equal(2, $collection[1]);
    }

    public function testOffsetSet()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);
        $collection[2] = 9;

        Assert::equal([ 1, 2, 9, 4, 5 ], $collection->toArray());
    }

    public function testOffsetUnset()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);
        unset($collection[2]);

        Assert::equal([ 1, 2, 4, 5 ], $collection->toArray());
    }


    /// ICollection

    public function testCount()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);
        Assert::equal(5, $collection->count());
    }

    public function testLimit()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal([1,2,3], $collection->limit(3)->toArray());
        Assert::equal([2,3,4], $collection->limit(3, 1)->toArray());
    }

    public function testEach()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        $expected = [1, 2, 3, 4, 5];
        $collection->each(function($item, $index) use ($expected) {
            Assert::equal($expected[$index], $item);
        });
    }

    public function testEvery()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::true($collection->every(function($item) { return is_int($item); }));
        Assert::false($collection->every(function($item) { return $item % 2 === 0; }));
    }

    public function testAny()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::true($collection->any(function($item) { return $item % 2 === 0; }));
        Assert::false($collection->any(function($item) { return $item > 10; }));
    }

    public function testFilter_byValue()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal([ 1, 3, 5], $collection->filter(function($item) { return $item % 2 === 1; })->toArray());
    }

    public function testFilter_byKey()
    {
        $collection = ArrayCollection::create([ 'x' => 'foo', 'xx' => 'bar', 'xxx' => 'baz' ]);

        Assert::equal([ 'xx' => 'bar', 'xxx' => 'baz' ], $collection->filter(function($value, $key) { return strlen($key) >= 2; })->toArray());
    }

    public function testFilterKeys()
    {
        $collection = ArrayCollection::create([ 'x' => 'foo', 'xx' => 'bar', 'xxx' => 'baz' ]);
        Assert::equal([ 'xx' => 'bar', 'xxx' => 'baz' ], $collection->filterKeys([ 'xx', 'xxx' ])->toArray());
    }

    public function testFirst_implicit()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal(1, $collection->first());
    }

    public function testFirst_func()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal(2, $collection->first(function($item) { return $item % 2 === 0; }));
    }

    public function testFirst_null()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::null($collection->first(function($item) { return $item > 10; }));
    }

    public function testFirst_empty()
    {
        $collection = ArrayCollection::create();

        Assert::null($collection->first());
    }

    public function testFlatten()
    {
        $collection = ArrayCollection::create([
            ArrayCollection::range(1, 2),
            ArrayCollection::range(3, 4),
            ArrayCollection::range(5, 6)
        ]);

        Assert::equal([ 1, 2, 3, 4, 5, 6 ], $collection->flatten()->toArray());
    }

    public function testIsEmpty()
    {
        Assert::true(ArrayCollection::create()->isEmpty());
        Assert::false(ArrayCollection::create(1, 2, 3, 4, 5)->isEmpty());
    }

    public function testKeys()
    {
        $collection = ArrayCollection::create([ 'x' => 'foo', 'xx' => 'bar', 'xxx' => 'baz' ]);

        Assert::equal([ 'x', 'xx', 'xxx' ], $collection->keys()->toArray());
    }

    public function testLast_func()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal(4, $collection->last(function($item) { return $item % 2 === 0; }));
    }

    public function testLast_null()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::null($collection->last(function($item) { return $item > 10; }));
    }

    public function testLast_empty()
    {
        $collection = ArrayCollection::create();

        Assert::null($collection->last());
    }

    public function testMap()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5)->map(function($item) {
            return $item * 2;
        });

        Assert::equal([ 2, 4, 6, 8, 10 ], $collection->toArray());
    }

    public function testMerge_collection()
    {
        $collection = ArrayCollection::create(1, 2, 3)
            ->merge(ArrayCollection::create(4, 5, 6));

        Assert::equal([ 1, 2, 3, 4, 5, 6 ], $collection->toArray());
    }

    public function testMerge_array()
    {
        $collection = ArrayCollection::create(1, 2, 3)->merge([ 4, 5, 6 ]);

        Assert::equal([ 1, 2, 3, 4, 5, 6 ], $collection->toArray());
    }

    public function testPad()
    {
        $collection = ArrayCollection::create(1, 2, 3);

        Assert::equal([ 1, 2, 3, 5, 5, 5 ], $collection->pad(6, 5)->toArray());
        Assert::equal([ 1, 2, 3 ], $collection->pad(2, 5)->toArray());
    }

    public function testPipe()
    {
        $collection = ArrayCollection::create(1, 2, 3);

        $called = false;
        $collection->pipe(function($col) use ($collection, &$called) {
            Assert::same($collection, $col);
            $called = true;
        });

        Assert::true($called);
    }

    public function testPop()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal(5, $collection->pop());
        Assert::equal([ 1, 2, 3, 4 ], $collection->toArray());
    }

    public function testPrepend()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        $collection->prepend(9);
        Assert::equal([ 9, 1, 2, 3, 4, 5 ], $collection->toArray());
    }

    public function testPush()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        $collection->push(9);
        Assert::equal([ 1, 2, 3, 4, 5, 9 ], $collection->toArray());
    }

    public function testReduce()
    {
        $expected = [ 1, 2, 3, 4, 5 ];
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        $index = 0;
        $result = $collection->reduce(function($carry, $value) use ($expected, &$index) {
            Assert::equal($expected[$index++], $value);
            return $carry + $value;
        }, 0);

        Assert::equal(15, $result);
    }

    public function testReverse()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal([ 5, 4, 3, 2, 1 ], $collection->reverse()->toArray());
    }

    public function testSearch_inline()
    {
        $collection = ArrayCollection::create('foo', 'bar', 'baz')->search('ba', function($current, $search) {
            return preg_match("/{$search}/i", $current);
        });

        Assert::equal([ 'bar', 'baz' ], $collection->toArray());
    }

    public function testSearch_func()
    {
        $collection = ArrayCollection::create('foo', 'bar', 'baz');
        $collection->searchFunc(function($current, $search) {
            return preg_match("/{$search}/i", $current);
        });

        $result = $collection->search('ba');

        Assert::equal([ 'bar', 'baz' ], $result->toArray());
    }

    public function testSearch_null()
    {
        $collection = ArrayCollection::create('foo', 'bar', 'baz');
        $result = $collection->search('ba');

        Assert::equal([ 'foo', 'bar', 'baz' ], $result->toArray());
    }

    public function testShift()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal(1, $collection->shift());
        Assert::equal([ 2, 3, 4, 5 ], $collection->toArray());
    }

    public function testSort()
    {
        $collection = ArrayCollection::create(5, 2, 1, 8, 6, 3, 7, 4)->sort(function($a, $b) {
            if ($a === $b)
                return 0;

            return $a < $b ? -1 : 1;
        });

        Assert::equal([ 1, 2, 3, 4, 5, 6, 7, 8 ], $collection->toArray());
    }

    public function testValues()
    {
        $collection = ArrayCollection::create([ 'x' => 'foo', 'xx' => 'bar', 'xxx' => 'baz' ]);

        Assert::equal([ 'foo', 'bar', 'baz' ], $collection->values()->toArray());
    }

    public function testToArray_scalar()
    {
        $collection = ArrayCollection::create(1, 2, 3, 4, 5);

        Assert::equal([ 1, 2, 3, 4, 5 ], $collection->toArray());
    }

    public function testToArray_ISerializable()
    {
        $mock = \Mockery::mock(ISerializable::class);
        $mock->shouldReceive('toArray')->andReturn([ 'name' => 'foo' ], [ 'name' => 'bar' ], [ 'name' => 'baz' ]);

        $collection = ArrayCollection::create([ $mock, $mock, $mock ]);

        Assert::equal([[ 'name' => 'foo' ], [ 'name' => 'bar' ], [ 'name' => 'baz' ]], $collection->toArray());
    }

    public function testToArray_ActiveRow()
    {
        $mock = \Mockery::mock(ActiveRow::class);
        $mock->shouldReceive('toArray')->andReturn([ 'name' => 'foo' ], [ 'name' => 'bar' ], [ 'name' => 'baz' ]);

        $collection = ArrayCollection::create([ $mock, $mock, $mock ]);

        Assert::equal([[ 'name' => 'foo' ], [ 'name' => 'bar' ], [ 'name' => 'baz' ]], $collection->toArray());
    }

    public function testToArray_object()
    {
        $collection = ArrayCollection::create([
            (object) [ 'name' => 'foo' ],
            (object) [ 'name' => 'bar' ],
            (object) [ 'name' => 'baz' ]
        ]);

        Assert::equal([[ 'name' => 'foo' ], [ 'name' => 'bar' ], [ 'name' => 'baz' ]], $collection->toArray());
    }

    public function testChunk()
    {
        $collection = ArrayCollection::range(1, 20);

        $i = 0;
        $collection->chunk(5, function($data, $index) use (&$i) {
            Assert::equal($i, $index);
            Assert::equal(ArrayCollection::range($i * 5 + 1, ($i + 1) * 5)->toArray(), $data->toArray());

            $i++;
        });
    }
}

(new ArrayCollectionTest())->run();
