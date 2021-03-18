<?php

namespace Varhall\Utilino\Utils;

use Varhall\Utilino\Collections\ArrayCollection;
use Varhall\Utilino\Collections\ArrayCollectionRecall;

class XmlCollection extends ArrayCollection
{
    use ArrayCollectionRecall;

    public function __construct($xml)
    {
        foreach ($xml as $item) {
            $this->push(new XmlElement($item));
        }
    }

    public static function create(...$values)
    {
        if (count($values) !== 1)
            throw new \InvalidArgumentException('Only single element can be used');

        return new static(...$values);
    }

    public function push($value)
    {
        return parent::push($value);
    }

    public function collection()
    {
        return $this;
    }
}