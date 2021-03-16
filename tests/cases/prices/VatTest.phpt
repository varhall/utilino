<?php

namespace Tests\Utilino\Prices;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Prices\IPrice;
use Varhall\Utilino\Prices\Vat;

require __DIR__ . '/../../bootstrap.php';

class VatTest extends TestCase
{
    public function testPrice_without()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('price')->andReturn(121);

        $price = new Vat($mock, 21, Vat::WITHOUT);

        Assert::equal(121, $price->price());
    }

    public function testPrice_with()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('price')->andReturn(121);

        $price = new Vat($mock, 21, Vat::WITH);

        Assert::equal(100.0, $price->price());
    }

    public function testVatPrice_without()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('price')->andReturn(100);

        $price = new Vat($mock, 21, Vat::WITHOUT);

        Assert::equal(121.0, $price->vat_price());
    }

    public function testVatPrice_with()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('price')->andReturn(121);

        $price = new Vat($mock, 21, Vat::WITH);

        Assert::equal(121, $price->vat_price());
    }

    public function testVat()
    {
        $mock = \Mockery::mock(IPrice::class);
        $price = new Vat($mock, 21, Vat::WITH);

        Assert::equal(21, $price->vat());
    }
}

(new VatTest())->run();
