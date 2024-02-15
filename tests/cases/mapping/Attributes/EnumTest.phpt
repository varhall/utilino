<?php

namespace Tests\Utilino\Mapping\Mappers;

use Nette\Schema\Expect;
use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Mapping\Attributes\Enum;
use Varhall\Utilino\Mapping\Attributes\Rule;
use Varhall\Utilino\Mapping\Mappers as M;

require __DIR__ . '/../../../bootstrap.php';

class EnumTest extends TestCase
{
    public function testVariadic()
    {
        $enum = new Enum('foo', 'bar', 'baz');
        $schema = Expect::anyOf('foo', 'bar', 'baz');

        Assert::equal($schema, $enum->schema());
    }


    public function testArray()
    {
        $enum = new Enum([ 'foo', 'bar', 'baz' ]);
        $schema = Expect::anyOf('foo', 'bar', 'baz');

        Assert::equal($schema, $enum->schema());
    }
}

(new EnumTest())->run();
