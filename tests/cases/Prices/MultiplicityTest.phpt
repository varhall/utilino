<?php

namespace Tests\Utilino\Prices;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Prices\IPrice;
use Varhall\Utilino\Prices\Multiplicity;

require __DIR__ . '/../../bootstrap.php';

class MultiplicityTest extends TestCase
{
    public function testPrice()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('price')->andReturn(100);
        $price = new Multiplicity($mock, 2);

        Assert::equal(200, $price->price());
    }

    public function testVatPrice()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('vat_price')->andReturn(100);
        $price = new Multiplicity($mock, 2);

        Assert::equal(200, $price->vat_price());
    }

    public function testVat()
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive('vat')->andReturn(21);
        $price = new Multiplicity($mock, 2);

        Assert::equal(21, $price->vat());
    }
}

(new MultiplicityTest())->run();
