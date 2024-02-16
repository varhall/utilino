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
use Varhall\Utilino\Mapping\Target;

require __DIR__ . '/../../bootstrap.php';

require __DIR__ . '/Classes/Address.php';
require __DIR__ . '/Classes/User.php';

class CasesTest extends TestCase
{
    protected function createTarget(callable $func, int|string $param = 0): Target
    {
        $reflection = new \ReflectionParameter($func, $param);
        return new Target($reflection);
    }

    public static function assertion(string $value): bool
    {
        return strlen($value) === 3;
    }


    /// TESTS ///

    public function testNumber_int()
    {
        $target = $this->createTarget(function (int $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        foreach ([10, '10'] as $value) {
            $result = $processor->process($schema, $value);
            Assert::equal(10, $result);
        }
    }

    public function testNumber_float()
    {
        $target = $this->createTarget(function (float $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        foreach ([10, 10.0, '10', '10.0', '10,0'] as $value) {
            $result = $processor->process($schema, $value);
            Assert::equal(10.0, $result);
        }
    }

    public function testNull()
    {
        $target = $this->createTarget(function (?int $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        foreach ([null, 'null', 'nil'] as $value) {
            $result = $processor->process($schema, $value);
            Assert::equal(null, $result);
        }
    }

    public function testBoolean()
    {
        $target = $this->createTarget(function (bool $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        foreach ([true, 1, 'true'] as $value) {
            $result = $processor->process($schema, $value);
            Assert::true($result);
        }

        foreach ([false, 0, 'false'] as $value) {
            $result = $processor->process($schema, $value);
            Assert::false($result);
        }
    }

    public function testDate()
    {
        $target = $this->createTarget(function (DateTime $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        $now = new DateTime(date('c'));
        foreach ([$now, DateTime::from($now), $now->format('c'), $now->getTimestamp()] as $value) {
            $result = $processor->process($schema, $value);
            Assert::equal($now, $result);
        }
    }

    public function testStructure_Simple()
    {
        $target = $this->createTarget(function (Address $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        $address = new Address();
        $address->street = 'Weissensee 18';
        $address->city = 'Berlin';

        Assert::equal($address, $processor->process($schema, (array) $address));
    }

    public function testStructure_Complex()
    {
        $target = $this->createTarget(function (User $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        $date = \Nette\Utils\DateTime::from('2023-11-24T10:00:00')->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        $expected = new User();
        $expected->name = 'Hans';
        $expected->surname = 'Muster';
        $expected->email = 'hans@gmail.com';
        $expected->age = 50;
        $expected->created = $date;
        $expected->address = new Address();
        $expected->address->street = 'Weissensee 18';
        $expected->address->city = 'Berlin';

        $data = json_decode(json_encode($expected), true);
        $data['created'] = $date->format('c');

        Assert::equal($expected, $processor->process($schema, $data));
    }

    public function testStructure_Additional()
    {
        $target = $this->createTarget(function (Address $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        $address = new Address();
        $address->street = 'Weissensee 18';
        $address->city = 'Berlin';

        Assert::equal($address, $processor->process($schema, [
            'street'    => 'Weissensee 18',
            'city'      => 'Berlin',
            'country'   => 'Germany'
        ]));
    }

    public function testStructure_Missing()
    {
        $target = $this->createTarget(function (Address $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        $address = new Address();
        $address->street = 'Weissensee 18';
        //$address->city = 'Berlin';

        Assert::equal($address, $processor->process($schema, [
            'street'    => 'Weissensee 18',
        ]));
    }

    public function testRule_email()
    {
        $target = $this->createTarget(function (#[Rule('email')] string $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        foreach (['foo@bar.com'] as $value) {
            $result = $processor->process($schema, $value);
            Assert::equal($value, $result);
        }

        foreach (['invalid'] as $value) {
            Assert::exception(fn() => $processor->process($schema, $value), ValidationException::class);
        }
    }

    public function testEnum()
    {
        $target = $this->createTarget(function (#[Enum('foo', 'bar', 'baz')] string $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        foreach (['foo', 'bar', 'baz'] as $value) {
            $result = $processor->process($schema, $value);
            Assert::equal($value, $result);
        }

        foreach (['invalid'] as $value) {
            Assert::exception(fn() => $processor->process($schema, $value), ValidationException::class);
        }
    }

    public function testAssert()
    {
        $target = $this->createTarget(function (#[\Varhall\Utilino\Mapping\Attributes\Assert([ self::class, 'assertion' ])] string $x) {});
        $schema = $target->schema();

        $processor = new Processor();

        Assert::equal('foo', $processor->process($schema, 'foo'));
        Assert::exception(fn() => $processor->process($schema, 'test'), ValidationException::class);
    }

    public function testImplicit()
    {
        $object = new class {
            #[Implicit('foo')]
            public string $value;
        };

        $schema = (new Structure($object))->schema();
        $processor = new Processor();

        Assert::equal($object, $processor->process($schema, []));
    }
}

(new CasesTest())->run();
