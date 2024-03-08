<?php

namespace Varhall\Utilino\Utils;

use Nette\Utils\DateTime;
use Varhall\Utilino\ISerializable;

class XmlElement implements \IteratorAggregate, ISerializable
{
    public \SimpleXMLElement|null $xml;

    public function __construct($xml)
    {
        if (is_string($xml)) {
            $xml = simplexml_load_string($xml);
        }

        $this->xml = $xml;
    }

    public function __get($name): static|XmlCollection
    {
        if ($this->xml && $this->xml->$name && $this->xml->$name->count() > 1) {
            return new XmlCollection($this->xml->$name);
        }

        return new static($this->xml ? $this->xml->$name : null);
    }

    public function getIterator(): \Traversable
    {
        $array = iterator_to_array($this->xml);

        return new \ArrayIterator(array_map(function($item) {
            return new static($item);
        }, $array));
    }

    public function value(): string
    {
        return $this->xml ? trim($this->xml->__toString()) : '';
    }

    public function number(): int|float|string
    {
        return is_numeric($this->value()) ? +$this->value() : $this->value();
    }

    public function date(): ?DateTime
    {
        return !empty($this->value()) ? new DateTime($this->value()) : null;
    }

    public function collection(): XmlCollection
    {
        return new XmlCollection($this->xml->count ? [ $this->xml ] : []);
    }

    public function select(string $xpath): static|XmlCollection|null
    {
        $result = $this->xml->xpath($xpath);

        if (empty($result)) {
            return null;
        }

        return count($result) > 1 ? new XmlCollection($result) : new static($result[0]);
    }

    public function attributes(): array
    {
        return json_decode(json_encode($this->xml->attributes()), true)['@attributes'] ?? [];
    }

    public function attribute(string $name): ?string
    {
        return $this->attributes()[$name] ?? null;
    }

    public function toXml(): string
    {
        return $this->xml->asXML();
    }

    public function toArray(): array
    {
        $array = json_decode(json_encode($this->xml), true);
        return $this->lowerKeys($array);
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    protected function lowerKeys(array $arr, int $case = CASE_LOWER): array
    {
        return array_map(function($item) use ($case){
            if (is_array($item)) {
                $item = $this->lowerKeys($item, $case);
            }

            return $item;
        }, array_change_key_case($arr, $case));
    }
}
