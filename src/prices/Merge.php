<?php

namespace Varhall\Utilino\Prices;


class Merge extends Decorator
{
    const FORCE = 'force';
    const SAME  = 'same';

    protected $merge = NULL;
    protected $method = self::FORCE;

    public function __construct(IPrice $price, IPrice $merge, $method = self::FORCE)
    {
        parent::__construct($price);

        if ($method === self::SAME && $price->vat() !== $merge->vat())
            throw new \InvalidArgumentException('Cannot merge prices with different VAT');

        $this->merge = $merge;
    }

    public function price()
    {
        return $this->price->price() + $this->merge->price();
    }

    public function vat_price()
    {
        return $this->price->vat_price() + $this->merge->vat_price();
    }

    public function vat()
    {
        return $this->price->vat() === $this->merge->vat() ? $this->price->vat() : NULL;
    }
}