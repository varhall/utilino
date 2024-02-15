<?php

namespace Varhall\Utilino\Prices;


class Price extends AbstractPrice
{
    use PriceFactory;

    protected $price = 0;

    public function __construct($price)
    {
        $this->price = $price;
    }


    public function price()
    {
        return $this->price;
    }

    public function vat_price()
    {
        return $this->price;
    }

    public function vat()
    {
        return 0;
    }
}