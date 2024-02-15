<?php

namespace Varhall\Utilino\Prices;


abstract class Decorator extends AbstractPrice
{
    protected $price = NULL;

    public function __construct(IPrice $price)
    {
        $this->price = $price;
    }
}