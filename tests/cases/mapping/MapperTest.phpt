<?php

namespace Tests\Utilino\Mapping;

use Nette\Schema\Processor;
use Nette\Schema\ValidationException;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;
use Tests\Utilino\Mapping\Classes\Address;
use Tests\Utilino\Mapping\Classes\User;
use Varhall\Utilino\Mapping\Attributes\Enum;
use Varhall\Utilino\Mapping\Attributes\Implicit;
use Varhall\Utilino\Mapping\Attributes\Required;
use Varhall\Utilino\Mapping\Attributes\Rule;
use Varhall\Utilino\Mapping\Attributes\Structure;
use Varhall\Utilino\Mapping\Mapper;
use Varhall\Utilino\Mapping\Target;

require __DIR__ . '/../../bootstrap.php';

class MapperTest extends TestCase
{
    public function testMap()
    {
        $object = new class {
            public string $value;
        };

        $result = Mapper::map(['value' => 'bar'], $object);

        $object->value = 'bar';
        Assert::equal($object, $result);
    }
}

(new MapperTest())->run();
