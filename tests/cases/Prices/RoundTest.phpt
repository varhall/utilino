<?php

namespace Tests\Utilino\Prices;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Prices\IPrice;
use Varhall\Utilino\Prices\Round;

require __DIR__ . '/../../bootstrap.php';

class RoundTest extends TestCase
{
    protected function create($method, $value)
    {
        $mock = \Mockery::mock(IPrice::class);
        $mock->shouldReceive($method)->andReturn($value);

        return $mock;
    }

    public function testPrice_Round()
    {
        Assert::equal(100.0, (new Round($this->create('price', 100.25)))->price());
        Assert::equal(100.0, (new Round($this->create('vat_price', 100.25)))->vat_price());

        Assert::equal(100.2, (new Round($this->create('price', 100.21), Round::TYPE_ROUND, 1))->price());
        Assert::equal(100.3, (new Round($this->create('price', 100.25), Round::TYPE_ROUND, 1))->price());
        Assert::equal(100.3, (new Round($this->create('price', 100.27), Round::TYPE_ROUND, 1))->price());
    }

    public function testPrice_Ceil()
    {
        Assert::equal(101.0, (new Round($this->create('price', 100.25), Round::TYPE_CEIL, 0))->price());
        Assert::equal(101.0, (new Round($this->create('vat_price', 100.25), Round::TYPE_CEIL, 0))->vat_price());

        Assert::equal(100.3, (new Round($this->create('price', 100.21), Round::TYPE_CEIL, 1))->price());
        Assert::equal(100.3, (new Round($this->create('price', 100.25), Round::TYPE_CEIL, 1))->price());
        Assert::equal(100.3, (new Round($this->create('price', 100.27), Round::TYPE_CEIL, 1))->price());
    }

    public function testPrice_Floor()
    {
        Assert::equal(100.0, (new Round($this->create('price', 100.25), Round::TYPE_FLOOR, 0))->price());
        Assert::equal(100.0, (new Round($this->create('vat_price', 100.25), Round::TYPE_FLOOR, 0))->vat_price());

        Assert::equal(100.2, (new Round($this->create('price', 100.21), Round::TYPE_FLOOR, 1))->price());
        Assert::equal(100.2, (new Round($this->create('price', 100.25), Round::TYPE_FLOOR, 1))->price());
        Assert::equal(100.2, (new Round($this->create('price', 100.27), Round::TYPE_FLOOR, 1))->price());
    }
}

(new RoundTest())->run();
