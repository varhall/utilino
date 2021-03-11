<?php

namespace Tests\Utilino\Utils;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Utils\Path;
use Varhall\Utilino\Utils\XmlElement;

require __DIR__ . '/../../bootstrap.php';

class XmlElementTest extends TestCase {

    const FILE = FIXTURES_DIR . '/sample1.xml';

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
            'car' => [
                [ 'manufacturer' => 'BMW', 'label' => 'ABC-123' ],
                [ 'manufacturer' => 'Audi', 'label' => 'XXX-321' ],
                [ 'manufacturer' => 'Mercedes', 'label' => 'YYY-666' ],
            ]
        ]
    ];

    protected function create()
    {
        return new XmlElement(simplexml_load_file(self::FILE));
    }

    public function testConstructString()
    {
        $xml = new XmlElement(file_get_contents(self::FILE));

        Assert::equal(simplexml_load_file(self::FILE)->asXML(), $xml->xml->asXML());
    }

    public function testConstructXml()
    {
        Assert::equal(simplexml_load_file(self::FILE)->asXML(), $this->create()->xml->asXML());
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
        $values = [ 'BMW', 'Audi', 'Mercedes' ];

        $index = 0;
        foreach ($this->create()->person->car as $car) {
            Assert::equal($values[$index++], $car->manufacturer->value());
        }
    }

    public function testCollection()
    {
        $values = [ 'BMW', 'Audi', 'Mercedes' ];

        $this->create()->person->car->each(function($item, $index) use ($values) {
            Assert::equal($values[$index], $item->manufacturer->value());
        });
    }

    public function testToXml()
    {
        Assert::equal(simplexml_load_file(self::FILE)->asXML(), $this->create()->toXml());
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
