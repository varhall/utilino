<?php

namespace Varhall\Utilino\Prices;


class Vat extends Decorator
{
    const WITHOUT   = 'without';
    const WITH      = 'with';

    protected $vat = 0;
    protected $type = self::WITHOUT;

    public function __construct(IPrice $price, $vat, $type = self::WITHOUT)
    {
        parent::__construct($price);

        $this->vat = $vat;
        $this->type = $type;
    }

    public function price()
    {
        $perc = ($this->type === self::WITH) ? (1 + ($this->vat / 100)) : 1;

        return $this->price->price() / $perc;
    }

    public function vat_price()
    {
        $perc = ($this->type === self::WITHOUT) ? (1 + ($this->vat / 100)) : 1;

        return $this->price->price() * $perc;
    }

    public function vat()
    {
        return $this->vat;
    }
}