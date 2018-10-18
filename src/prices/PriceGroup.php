<?php

namespace Varhall\Utilino\Prices;


use Varhall\Utilino\Collections\ArrayCollection;

class PriceGroup extends AbstractPrice
{
    protected $prices = NULL;

    protected $discount = 0;


    public static function create($prices, $discount = 0)
    {
        $instance = new static();

        $instance->prices = ArrayCollection::create($prices);
        $instance->discount = self::toNumber($discount);

        return $instance;
    }

    public function price()
    {
        return max($this->native_price() - $this->discount(), 0);
    }

    public function vat_price()
    {
        // TODO: this is not a correct formula (VAT is not applied properly on the discount)
        return max($this->sumarize(__FUNCTION__) - $this->discount,0);
    }


    public function vat()
    {
        return $this->prices
            ->map(function($price) { return $price->vat; })
            ->pipe(function($prices) {
                return ArrayCollection::create(array_unique($prices->toArray()));
            })
            ->pipe(function($vats) {
                return $vats->count() === 1 ? $vats->first() : NULL;
            });
    }

    public function discount()
    {
        return $this->sumarize(__FUNCTION__) + $this->discount;
    }

    public function native_price()
    {
        return $this->sumarize(__FUNCTION__);
    }

    public function native_vat_price()
    {
        return $this->sumarize(__FUNCTION__);
    }

    public function add(AbstractPrice $price)
    {
        return static::create($this->prices->merge([ $price ]));
    }

    public function multiply($count)
    {
        return static::create($this->prices->map(function($price) use ($count) { return $price->multiply($count); }));
    }


    protected function sumarize($property)
    {
        return $this->prices
            ->map(function($price) use ($property) { return call_user_func([$price, $property]); })
            ->reduce(function($carry, $value) { return $carry + $value; }, 0);
    }

}