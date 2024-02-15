<?php

namespace Tests\Utilino\Mapping\Mappers;

use Nette\Schema\Expect;
use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Mapping\Attributes\Rule;
use Varhall\Utilino\Mapping\Mappers as M;

require __DIR__ . '/../../../bootstrap.php';

class RuleTest extends TestCase
{
    private array $mappers;

    public function __construct()
    {
        $this->mappers = [
            'int'           => M\IntNumber::class,
            'integer'       => M\IntNumber::class,

            'number'        => M\FloatNumber::class,
            'double'        => M\FloatNumber::class,
            'float'         => M\FloatNumber::class,

            'bool'          => M\Boolean::class,
            'boolean'       => M\Boolean::class,
        ];
    }

    public function testBasic()
    {
        foreach (array_keys($this->mappers) as $type) {
            $rule = new Rule($type);
            $mapper = new $this->mappers[$type];
            $schema = Expect::type($type)->before([ $mapper, 'apply' ]);

            Assert::equal($schema, $rule->schema());
        }
    }

    public function testRange()
    {
        foreach (['number', 'int', 'integer', 'double', 'float'] as $type) {
            $rule = new Rule("{$type}:0..10");
            $mapper = new $this->mappers[$type];

            $schema = Expect::type("{$type}:0..10")->before([ $mapper, 'apply' ]);;

            Assert::equal($schema, $rule->schema());
        }
    }

    public function testNullable_pipe()
    {
        $rule = new Rule("int|null");
        $mapper = new M\IntNumber();

        $schema = Expect::type("int|null")->before([ $mapper, 'apply' ]);;

        Assert::equal($schema, $rule->schema());
    }

    public function testNullable_question()
    {
        $rule = new Rule("?int");
        $mapper = new M\IntNumber();

        $schema = Expect::type("?int")->before([ $mapper, 'apply' ]);

        Assert::equal($schema, $rule->schema());
    }
}

(new RuleTest())->run();
