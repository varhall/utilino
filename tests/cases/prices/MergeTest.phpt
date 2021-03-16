<?php

namespace Tests\Utilino\Prices;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Prices\IPrice;
use Varhall\Utilino\Prices\Merge;

require __DIR__ . '/../../bootstrap.php';

class MergeTest extends TestCase
{
    public function testPrice()
    {
        $first = \Mockery::mock(IPrice::class);
        $first->shouldReceive('price')->andReturn(100);

        $second = \Mockery::mock(IPrice::class);
        $second->shouldReceive('price')->andReturn(200);

        $price = new Merge($first, $second);

        Assert::equal(300, $price->price());
    }

    public function testVatPrice()
    {
        $first = \Mockery::mock(IPrice::class);
        $first->shouldReceive('vat_price')->andReturn(100);

        $second = \Mockery::mock(IPrice::class);
        $second->shouldReceive('vat_price')->andReturn(200);

        $price = new Merge($first, $second);

        Assert::equal(300, $price->vat_price());
    }

    public function testVat_same()
    {
        $first = \Mockery::mock(IPrice::class);
        $first->shouldReceive('vat_price')->andReturn(100);
        $first->shouldReceive('vat')->andReturn(21);

        $second = \Mockery::mock(IPrice::class);
        $second->shouldReceive('vat_price')->andReturn(200);
        $second->shouldReceive('vat')->andReturn(21);

        $price = new Merge($first, $second);

        Assert::equal(21, $price->vat());
    }

    public function testVat_different()
    {
        $first = \Mockery::mock(IPrice::class);
        $first->shouldReceive('vat_price')->andReturn(100);
        $first->shouldReceive('vat')->andReturn(21);

        $second = \Mockery::mock(IPrice::class);
        $second->shouldReceive('vat_price')->andReturn(200);
        $second->shouldReceive('vat')->andReturn(15);

        $price = new Merge($first, $second);

        Assert::null($price->vat());
    }
}

(new MergeTest())->run();
