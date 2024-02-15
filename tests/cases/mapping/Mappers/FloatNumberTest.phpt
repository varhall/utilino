<?php

namespace Tests\Utilino\Mapping\Mappers;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Mapping\Mappers\FloatNumber;

require __DIR__ . '/../../../bootstrap.php';

class FloatNumberTest extends TestCase
{
    public function testInteger()
    {
        $mapper = new FloatNumber();

        Assert::equal(10.0, $mapper->apply(10));
        Assert::equal(10.0, $mapper->apply('10'));
    }

    public function testFloat()
    {
        $mapper = new FloatNumber();

        Assert::equal(10.5, $mapper->apply(10.5));
        Assert::equal(10.5, $mapper->apply('10.5'));
    }

    public function testDecimalPoint()
    {
        $mapper = new FloatNumber();

        Assert::equal(10.5, $mapper->apply('10.5'));
        Assert::equal(10.5, $mapper->apply('10,5'));
    }
}

(new FloatNumberTest())->run();
