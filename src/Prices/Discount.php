<?php

namespace Varhall\Utilino\Prices;


class Discount extends Decorator
{
    protected $discount = 0;

    public function __construct(IPrice $price, $discount)
    {
        parent::__construct($price);

        $this->discount = $discount;
    }

    public function price()
    {
        $discount = $this->discount / (1 + ($this->price->vat() / 100));

        return max($this->price->price() - $discount, 0);
    }

    public function vat_price()
    {
        return max($this->price->vat_price() - $this->discount, 0);
    }

    public function vat()
    {
        return $this->price->vat();
    }
}