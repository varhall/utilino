<?php

namespace Varhall\Utilino\Prices;

class Price extends AbstractPrice
{
    protected $price                = NULL;
    protected $vat                  = NULL;
    protected $discount             = NULL;

    public static function base($price, $vat, $discount = 0)
    {
        $instance = new static();

        $instance->price = self::toNumber($price);
        $instance->vat = self::toNumber($vat);
        $instance->discount = self::toNumber($discount);

        return $instance;
    }

    public static function taxed($vatPrice, $vat, $vatDiscount = 0)
    {
        $instance = new static();

        $vatCoefficient = (1 + ($vat / 100));

        $instance->price = ($vatPrice / $vatCoefficient);
        $instance->vat = $vat;
        $instance->discount = $vatDiscount / $vatCoefficient;

        return $instance;
    }


    public function price()
    {
        return max($this->price - $this->discount, 0);
    }

    public function vat_price()
    {
        return $this->applyVat($this->price(), $this->vat);
    }

    public function vat()
    {
        return $this->vat;
    }

    public function discount()
    {
        return $this->discount;
    }

    public function native_price()
    {
        return $this->price;
    }

    public function native_vat_price()
    {
        return $this->applyVat($this->price, $this->vat);
    }


    protected function applyVat($price)
    {
        return $price * (1 + ($this->vat / 100));
    }


    /// OPERATIONS

    public function add(AbstractPrice $price)
    {
        if ($this->vat !== $price->vat)
            return PriceGroup::create([ $this, $price ]);

        return static::base($this->price + $price->price, $this->vat, $this->discount + $price->discount);
    }

    public function multiply($count)
    {
        return static::base($this->price * $count, $this->vat, $this->discount * $count);
    }
}