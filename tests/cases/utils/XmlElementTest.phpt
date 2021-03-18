<?php

namespace Tests\Utilino\Utils;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Utils\Path;
use Varhall\Utilino\Utils\XmlElement;

require __DIR__ . '/../../bootstrap.php';

class XmlElementTest extends TestCase {

    const DEFAULT = FIXTURES_DIR . '/sample1.xml';
    const COLLECTIONS = FIXTURES_DIR . '/sample2.xml';

    protected $expected = [
        'person' => [
            'name' => 'Pepa',
            'surname' => 'Novak',
            'salary' => '25000',
            'birthdate' => '1990-05-15',
            'address' => [
                'street' => 'Bila 15',
                'city' => 'Prague',
                'zip' => '150 00'
            ],
            'emails' => [
                'email' => [
                    'pepa@gmail.com',
                    'pepa.novak@cmp.com',
                    'novak@company.cz'
                ]
                /*'email' => [
                    [ '@attributes' => ['type' => 'private'], 'pepa@gmail.com' ],
                    [ '@attributes' => ['type' => 'work'], 'pepa.novak@cmp.com' ],
                    [ '@attributes' => ['type' => 'work'], 'novak@company.cz' ]
                ]*/
            ],
            'car' => [
                [ '@attributes' => ['type' => 'primary'], 'manufacturer' => 'BMW', 'label' => 'ABC-123' ],
                [ '@attributes' => ['type' => 'secondary'], 'manufacturer' => 'Audi', 'label' => 'XXX-321' ],
                [ '@attributes' => ['type' => 'hobby'], 'manufacturer' => 'Ferrari', 'label' => 'YYY-666' ],
            ]
        ]
    ];

    protected function create()
    {
        return new XmlElement(simplexml_load_file(self::DEFAULT));
    }

    protected function createCollection()
    {
        return new XmlElement(simplexml_load_file(self::COLLECTIONS));
    }

    public function testConstructString()
    {
        $xml = new XmlElement(file_get_contents(self::DEFAULT));

        Assert::equal(simplexml_load_file(self::DEFAULT)->asXML(), $xml->xml->asXML());
    }

    public function testConstructXml()
    {
        Assert::equal(simplexml_load_file(self::DEFAULT)->asXML(), $this->create()->xml->asXML());
    }

    public function testValue()
    {
        Assert::equal('Pepa', $this->create()->person->name->value());
    }

    public function testNumber()
    {
        Assert::equal(25000, $this->create()->person->salary->number());
    }

    public function testDate()
    {
        Assert::equal('1990-05-15', $this->create()->person->birthdate->date()->format('Y-m-d'));
    }

    public function testNested()
    {
        Assert::equal('Prague', $this->create()->person->address->city->value());
    }

    public function testLoop()
    {
        $values = [ 'BMW', 'Audi', 'Ferrari' ];

        $index = 0;
        foreach ($this->create()->person->car as $car) {
            Assert::equal($values[$index++], $car->manufacturer->value());
        }
    }

    public function testCollection()
    {
        $values = [ 'BMW', 'Audi', 'Ferrari' ];

        $this->create()->person->car->each(function($item, $index) use ($values) {
            Assert::equal($values[$index], $item->manufacturer->value());
        });
    }

    public function testCollection_single()
    {
        $expected = [ 'single' ];
        $this->createCollection()->single->collection()->each(function($item, $index) use ($expected) {
            Assert::equal($expected[$index], $item->value());
        });
    }

    public function testCollection_multi()
    {
        $expected = [ 'multi 1', 'multi 2', 'multi 3' ];
        $this->createCollection()->multi->collection()->each(function($item, $index) use ($expected) {
            Assert::equal($expected[$index], $item->value());
        });
    }

    public function testToXml()
    {
        Assert::equal(simplexml_load_file(self::DEFAULT)->asXML(), $this->create()->toXml());
    }

    public function testToArray()
    {
        Assert::equal($this->expected, $this->create()->toArray());
    }

    public function testToJson()
    {
        Assert::equal(json_encode($this->expected), $this->create()->toJson());
    }
}

(new XmlElementTest())->run();
