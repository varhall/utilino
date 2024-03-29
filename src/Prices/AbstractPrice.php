<?php

namespace Varhall\Utilino\Prices;


abstract class AbstractPrice implements IPrice
{
    public function __get($name)
    {
        if (method_exists($this, $name))
            return call_user_func([$this, $name]);

        throw new \BadMethodCallException("Property $name not found");
    }

    public function toArray()
    {
        return [
            'price'     => $this->price(),
            'vat_price' => $this->vat_price(),
            'vat'       => $this->vat()
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function add(IPrice $price, $method = Merge::FORCE)
    {
        return new Merge($this, $price, $method);
    }

    public function subtract(IPrice $price, $method = Merge::FORCE)
    {
        return $this->add(new Multiplicity($price, -1), $method);
    }
}