<?php

namespace Tests\Utilino\Mapping\Mappers;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Mapping\Mappers\IntNumber;

require __DIR__ . '/../../../bootstrap.php';

class IntNumberTest extends TestCase
{
    public function testInteger()
    {
        $mapper = new IntNumber();

        Assert::equal(10, $mapper->apply(10));
        Assert::equal(10, $mapper->apply('10'));
    }
}

(new IntNumberTest())->run();
