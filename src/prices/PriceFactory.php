<?php

namespace Varhall\Utilino\Prices;


trait PriceFactory
{
    public static function basic($price, $vat = 0)
    {
        return new Vat(new Price($price), $vat, Vat::WITHOUT);
    }

    public static function taxed($price, $vat = 0)
    {
        return new Vat(new Price($price), $vat, Vat::WITH);
    }
}