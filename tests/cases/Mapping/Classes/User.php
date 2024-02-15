<?php

namespace Tests\Utilino\Mapping\Classes;

use Varhall\Utilino\Mapping\Attributes\Required;
use Varhall\Utilino\Mapping\Attributes\Rule;

class User
{
    #[Rule('string:3..')]
    public string $name;

    #[Rule('string:1..')]
    public string $surname;

    #[Rule('email')]
    #[Required]
    public string $email;

    #[Rule('int')]
    public $age;

    public \DateTime $created;

    public Address $address;
}