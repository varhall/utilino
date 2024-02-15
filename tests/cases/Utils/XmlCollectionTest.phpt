<?php

namespace Tests\Utilino\Utils;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Utils\XmlCollection;

require __DIR__ . '/../../bootstrap.php';

class XmlCollectionTest extends TestCase {

    const FILE = FIXTURES_DIR . '/sample2.xml';

    protected function xml()
    {
        return simplexml_load_file(self::FILE);
    }

    public function testConstruct()
    {
        $expected = [ 'multi 1', 'multi 2', 'multi 3' ];
        $collection = new XmlCollection($this->xml()->multi);

        Assert::equal(3, $collection->count());

        $collection->each(function($item, $index) use ($expected) {
           Assert::equal($expected[$index], $item->value());
        });
    }

    public function testCreate_xml()
    {
        $expected = [ 'multi 1', 'multi 2', 'multi 3' ];
        $collection = XmlCollection::create($this->xml()->multi);

        Assert::equal(3, $collection->count());

        $collection->each(function($item, $index) use ($expected) {
            Assert::equal($expected[$index], $item->value());
        });
    }

    public function testCreate_array()
    {
        $collection = XmlCollection::create([ $this->xml()->single ]);

        Assert::equal(1, $collection->count());

        $expected = [ 'single' ];
        $collection->each(function($item, $index) use ($expected) {
            Assert::equal($expected[$index], $item->value());
        });
    }

    public function testCreate_multiargs()
    {
        Assert::exception(function() {
            XmlCollection::create(1, 2, 3);
        }, \InvalidArgumentException::class);


        Assert::exception(function() {
            XmlCollection::create();
        }, \InvalidArgumentException::class);
    }

    public function testCollection()
    {
        $collection = XmlCollection::create([ $this->xml()->single ]);
        Assert::same($collection, $collection->collection());
    }

    public function testMap()
    {
        $expected = [ '1', '2', '3' ];
        $collection = XmlCollection::create($this->xml()->multi)->map(function($item) {
            return str_replace('multi ', '', $item->value());
        });

        Assert::equal(3, $collection->count());
        $collection->each(function($item, $index) use ($expected) {
            Assert::equal($expected[$index], $item);
        });
    }
}

(new XmlCollectionTest())->run();
