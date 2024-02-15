<?php

namespace Tests\Utilino\Mapping\Mappers;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Mapping\Mappers\Boolean;

require __DIR__ . '/../../../bootstrap.php';

class BooleanTest extends TestCase
{
    public function testTrue()
    {
        $mapper = new Boolean();

        Assert::true($mapper->apply(true));
        Assert::true($mapper->apply(1));
        Assert::true($mapper->apply('true'));
        Assert::true($mapper->apply('yes'));
    }

    public function testFalse()
    {
        $mapper = new Boolean();

        Assert::false($mapper->apply(false));
        Assert::false($mapper->apply(0));
        Assert::false($mapper->apply('false'));
        Assert::false($mapper->apply('no'));
    }
}

(new BooleanTest())->run();
