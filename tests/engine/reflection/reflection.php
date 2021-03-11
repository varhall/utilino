<?php

namespace Tests\Utilino\Engine;

class Encapsulated
{
    use YesTrait;

    private $name = 'hello';

    public function getName()
    {
        return $this->name;
    }

    private function methodWithArgs($a, $b)
    {
        return $a + $b;
    }

    private function methodWithoutArgs()
    {
        return 'hello';
    }
}

class EncapsulatedChild extends Encapsulated
{

}

trait YesTrait {

}

trait NoTrait {

}
