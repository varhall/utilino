<?php

namespace Tests\Utilino\Mapping\Mappers;

use Nette\Schema\Expect;
use Tester\Assert;
use Tester\TestCase;
use Tests\Utilino\Mapping\Classes\Address;
use Tests\Utilino\Mapping\Classes\User;
use Varhall\Utilino\Mapping\Attributes\Structure;
use Varhall\Utilino\Mapping\Mappers\Number;

require __DIR__ . '/../../../bootstrap.php';

require __DIR__ . '../../Classes/Address.php';
require __DIR__ . '../../Classes/User.php';

class StructureTest extends TestCase
{
    public function testSimple()
    {
        $structure = new Structure(Address::class);

        $schema = Expect::from(new Address(), [
            'street' => Expect::type('string')->transform(fn($x) => $x),//->required(),
            'city' => Expect::type('string')->transform(fn($x) => $x),//->required()
        ])
            ->skipDefaults()
            ->before(fn($x) => $x);

        Assert::equal($schema, $structure->schema());
    }

    /*
    public function testComplex()
    {
        $structure = new Structure(User::class);

        $schema = Expect::from(new User(), [
            'name' => Expect::type('string:3..')->transform(fn($x) => $x),
            'surname' => Expect::type('string:1..')->transform(fn($x) => $x),
            'email' => Expect::type('email')->transform(fn($x) => $x),
            'age' => Expect::anyOf(
                Expect::type('int')->before([ new Number(), 'apply' ]),
                'null',
                'nil'
            )->nullable()->before(fn($x) => $x),
            'created' => Expect::from(new \DateTime())->transform(fn($x) => $x),
            'address' => Expect::from(new Address(), [
                'street' => Expect::type('string')->transform(fn($x) => $x),
                'city' => Expect::type('string')->transform(fn($x) => $x),
            ])->transform(fn($x) => $x)
        ])->skipDefaults();

        Assert::equal($schema, $structure->schema());
    }
    */
}

(new StructureTest())->run();
