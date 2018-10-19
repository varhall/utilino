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

        return static::base($this->price + $price->price(), $this->vat, $this->discount + $price->discount());
    }

    public function multiply($count)
    {
        return static::base($this->price * $count, $this->vat, $this->discount * $count);
    }



    /*
    public abstract function discount();

    public abstract function vat();

    public abstract function price();

    public abstract function price_vat();

    public abstract function price_discount();

    public abstract function price_discount_vat();


    protected function applyVat($price)
    {
        return $price * (1 + ($this->vat / 100));
    }*/


    /*public $price       = 0;

    public $vat         = 0;

    public $discount    = 0;


    public function __construct($price = 0, $vat = 0, $discount = 0)
    {
        $this->price = $price;
        $this->vat = $vat;
        $this->discount = $discount;
    }

    public function __get($name)
    {
        if (method_exists($this, $name))
            return call_user_func([$this, $name]);

        throw new \BadMethodCallException("Property $name not found");
    }

    /// PRICES

    public function price_vat()
    {
        return $this->applyVat($this->price);
    }

    public function price_discount()
    {
        return max($this->price - $this->discount, 0);
    }

    public function price_discount_vat()
    {
        return $this->applyVat($this->price_discount());
    }

    protected function applyVat($price)
    {
        return $price * (1 + ($this->vat / 100));
    }


    /// OPERATIONS

    public function add(Price $price)
    {
        if ($this->vat !== $price->vat)
            throw new \InvalidArgumentException('VATs must be same');

        $this->price += $price->price;
        $this->sale += $price->sale;

        return $this;
    }

    public function multiply($count)
    {
        $this->price *= $count;
        $this->sale *= $count;

        return $this;
    }*/



}