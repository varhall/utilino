<?php

namespace Tests\Utilino\Utils;

use Tester\Assert;
use Tester\TestCase;
use Tests\Utilino\Engine\Encapsulated;
use Tests\Utilino\Engine\EncapsulatedChild;
use Tests\Utilino\Engine\NoTrait;
use Tests\Utilino\Engine\YesTrait;
use Varhall\Utilino\Utils\Reflection;

require __DIR__ . '/../../bootstrap.php';

class ReflectionTest extends TestCase
{
    public function testRead()
    {
        $object = new Encapsulated();

        Assert::equal('hello', Reflection::readPrivateProperty($object, 'name'));
    }

    public function testWrite()
    {
        $object = new Encapsulated();

        Reflection::writePrivateProperty($object, 'name', 'world');

        Assert::equal('world', $object->getName());
        Assert::equal('world', Reflection::readPrivateProperty($object, 'name'));
    }

    public function testChild()
    {
        $object = new EncapsulatedChild();

        Reflection::writePrivateProperty($object, 'name', 'world');

        Assert::equal('world', $object->getName());
        Assert::equal('world', Reflection::readPrivateProperty($object, 'name'));
    }

    public function testMethodWithoutArgs()
    {
        $object = new Encapsulated();

        $result = Reflection::callPrivateMethod($object, 'methodWithoutArgs');
        Assert::equal('hello', $result);
    }

    public function testMethodWithArgs()
    {
        $object = new Encapsulated();

        $result = Reflection::callPrivateMethod($object, 'methodWithArgs', [ 1, 2 ]);
        Assert::equal(3, $result);
    }

    public function testChildMethod()
    {
        $object = new EncapsulatedChild();

        $result = Reflection::callPrivateMethod($object, 'methodWithArgs', [ 1, 2 ]);
        Assert::equal(3, $result);
    }

    public function testTrait()
    {
        Assert::true(Reflection::hasTrait(new Encapsulated(), YesTrait::class));
        Assert::false(Reflection::hasTrait(new Encapsulated(), NoTrait::class));
    }

    public function testTraitChild()
    {
        Assert::true(Reflection::hasTrait(new EncapsulatedChild(), YesTrait::class));
        Assert::false(Reflection::hasTrait(new EncapsulatedChild(), NoTrait::class));
    }
}

(new ReflectionTest())->run();
