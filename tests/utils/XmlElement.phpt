<?php

namespace Varhall\Utilino\Tests\Utils;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Utils\Path;
use Varhall\Utilino\Utils\XmlElement;

require __DIR__ . '/../bootstrap.php';

class XmlElementTest extends TestCase {

    protected $element = null;

    protected function setUp()
    {
        $this->element = new XmlElement(simplexml_load_file('sample1.xml'));
    }

    public function testValue()
    {
        Assert::equal('Pepa', $this->element->person->name->value());
    }

    public function testNumber()
    {
        Assert::equal(25000, $this->element->person->salary->number());
    }

    public function testDate()
    {
        Assert::equal('1990-05-15', $this->element->person->birthdate->date()->format('Y-m-d'));
    }

    public function testNested()
    {
        Assert::equal('Prague', $this->element->person->address->city->value());
    }

    public function testLoop()
    {
        $values = [ 'BMW', 'Audi', 'Mercedes' ];

        $index = 0;
        foreach ($this->element->person->car as $car) {
            Assert::equal($values[$index++], $car->manufacturer->value());
        }
    }

    public function testCollection()
    {
        $values = [ 'BMW', 'Audi', 'Mercedes' ];

        $this->element->person->car->each(function($item, $index) use ($values) {
            Assert::equal($values[$index], $item->manufacturer->value());
        });
    }
}

(new XmlElementTest())->run();
