<?php

namespace Varhall\Utilino\Prices;


use Varhall\Utilino\ISerializable;

abstract class AbstractPrice implements ISerializable
{
    protected static function toNumber($value)
    {
        return is_numeric($value) ? floatval($value) : $value;
    }

    /**
     * Default price excluding VAT (price to be paid)
     *
     * @return float
     */
    public abstract function price();

    /**
     * Default price including VAT (price to be paid)
     *
     * @return float
     */
    public abstract function vat_price();

    /**
     * VAT in percent (e.g 21)
     *
     * @return int
     */
    public abstract function vat();

    /**
     * Amount of the discount
     *
     * @return float
     */
    public abstract function discount();

    /**
     * Original undiscounted price excluding VAT
     *
     * @return float
     */
    public abstract function native_price();

    /**
     * Original undiscounted price including VAT
     *
     * @return float
     */
    public abstract function native_vat_price();

    public abstract function add(AbstractPrice $price);

    public abstract function multiply($count);


    public function __get($name)
    {
        if (method_exists($this, $name))
            return call_user_func([$this, $name]);

        throw new \BadMethodCallException("Property $name not found");
    }


    /// ISerializable

    public function toArray()
    {
        $array = [];

        foreach (['price', 'vat_price', 'native_price', 'native_vat_price', 'vat', 'discount'] as $key) {
            $array[$key] = round(call_user_func([$this, $key]), 3);
        }


        return $array;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}