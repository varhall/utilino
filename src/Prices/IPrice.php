<?php

namespace Varhall\Utilino\Prices;

use Varhall\Utilino\ISerializable;

interface IPrice extends ISerializable
{
    public function price();

    public function vat_price();

    public function vat();
}