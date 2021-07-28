<?php

namespace Varhall\Utilino\Prices;


class Round extends Decorator
{
    const TYPE_ROUND    = 'round';
    const TYPE_CEIL     = 'ceil';
    const TYPE_FLOOR    = 'floor';

    /** @var string */
    protected $type;

    /** @var int */
    protected $decimals;


    public function __construct(IPrice $price, string $type = self::TYPE_ROUND, int $decimals = 0)
    {
        parent::__construct($price);

        $this->type = $type;
        $this->decimals = $decimals;
    }

    public function price()
    {
        return $this->transform($this->price->price());
    }

    public function vat_price()
    {
        return $this->transform($this->price->vat_price());
    }

    public function vat()
    {
        return $this->price->vat();
    }

    protected function transform(float $value): float
    {
        if ($this->type === self::TYPE_ROUND)
            return round($value, $this->decimals);

        if ($this->type === self::TYPE_CEIL)
            return ceil($value * pow(10, $this->decimals)) / pow(10, $this->decimals);

        if ($this->type === self::TYPE_FLOOR)
            return floor($value * pow(10, $this->decimals)) / pow(10, $this->decimals);

        return $value;
    }
}