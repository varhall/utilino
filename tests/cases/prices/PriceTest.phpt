<?php

namespace Tests\Utilino\Prices;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Prices\Price;

require __DIR__ . '/../../bootstrap.php';

class PriceTest extends TestCase
{
    public function testConstruct()
    {
        $price = new Price(100);

        Assert::equal(100, $price->price);
        Assert::equal(100, $price->vat_price);
        Assert::equal(0, $price->vat);
    }

    public function testCreateBasic()
    {
        $price = Price::basic(100, 21);

        Assert::equal(100, $price->price);
        Assert::equal(121.0, $price->vat_price);
        Assert::equal(21, $price->vat);
    }

    public function testCreateTaxed()
    {
        $price = Price::taxed(121, 21);

        Assert::equal(100.0, $price->price);
        Assert::equal(121, $price->vat_price);
        Assert::equal(21, $price->vat);
    }

    public function testAdd()
    {
        $price = Price::taxed(121, 21)->add(Price::taxed(242, 21));

        Assert::equal(300.0, $price->price);
        Assert::equal(363, $price->vat_price);
        Assert::equal(21, $price->vat);
    }

    public function testSubtract()
    {
        $price = Price::taxed(363, 21)->subtract(Price::taxed(121, 21));

        Assert::equal(200.0, $price->price);
        Assert::equal(242, $price->vat_price);
        Assert::equal(21, $price->vat);
    }

    public function testToArray()
    {
        $price = new Price(100);

        Assert::equal([ 'price' => 100, 'vat_price' => 100, 'vat' => 0 ], $price->toArray());
    }

    public function testToJson()
    {
        $price = new Price(100);

        Assert::equal(json_encode([ 'price' => 100, 'vat_price' => 100, 'vat' => 0 ]), $price->toJson());
    }
}

(new PriceTest())->run();
