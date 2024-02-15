<?php

namespace Varhall\Utilino\Prices;


class Multiplicity extends Decorator
{
    protected $count = 1;

    public function __construct(IPrice $price, $count)
    {
        parent::__construct($price);

        $this->count = $count;
    }

    public function price()
    {
        return $this->price->price() * $this->count;
    }

    public function vat_price()
    {
        return $this->price->vat_price() * $this->count;
    }

    public function vat()
    {
        return $this->price->vat();
    }
}