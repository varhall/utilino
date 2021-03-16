<?php

namespace Tests\Utilino\Prices;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Prices\Discount;
use Varhall\Utilino\Prices\IPrice;
use Varhall\Utilino\Prices\Vat;

require __DIR__ . '/../../bootstrap.php';

class DiscountTest extends TestCase
{
    public function testPrice_positive()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('price')->andReturn(100);
        $mock->shouldReceive('vat')->andReturn(21);

        $price = new Discount($mock, 40);

        Assert::equal(66.94214876033058, $price->price());
    }

    public function testPrice_negative()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('price')->andReturn(100);
        $mock->shouldReceive('vat')->andReturn(21);

        $price = new Discount($mock, 150);

        Assert::equal(0, $price->price());
    }

    public function testVatPrice_positive()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('vat_price')->andReturn(100);

        $price = new Discount($mock, 40);

        Assert::equal(60, $price->vat_price());
    }

    public function testVatPrice_negative()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('vat_price')->andReturn(100);

        $price = new Discount($mock, 150);

        Assert::equal(0, $price->vat_price());
    }

    public function testVat()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('vat')->andReturn(21);

        $price = new Discount($mock, 21, Vat::WITH);

        Assert::equal(21, $price->vat());
    }
}

(new DiscountTest())->run();
