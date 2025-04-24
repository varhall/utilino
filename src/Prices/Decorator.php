<?php

namespace Varhall\Utilino\Prices;


abstract class Decorator extends AbstractPrice
{
    protected IPrice $price;

    public function __construct(IPrice $price)
    {
        $this->price = $price;
    }
}