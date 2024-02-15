<?php

namespace Varhall\Utilino\Mapping\Attributes;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Varhall\Utilino\Mapping\Mappers as M;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class Rule implements IBase
{
    protected array $mappers;
    protected string $type;


    public function __construct(string $type)
    {
        $this->type = $type;

        $this->mappers = [
            'int'           => M\IntNumber::class,
            'integer'       => M\IntNumber::class,

            'number'        => M\FloatNumber::class,
            'double'        => M\FloatNumber::class,
            'float'         => M\FloatNumber::class,

            'bool'          => M\Boolean::class,
            'boolean'       => M\Boolean::class,
        ];
    }

    public function schema(): Schema
    {
        $schema = Expect::type($this->type);

        $base = $this->getBaseRule();
        if (array_key_exists($base, $this->mappers)) {
            //$schema = $schema->before(fn($value) => (new $this->mappers[$base])->apply($value));
            $mapper = new $this->mappers[$base];
            $schema = $schema->before([ $mapper, 'apply' ]);
        }

        return $schema;
    }

    protected function getBaseRule(): string
    {
        $rule = explode(':', $this->type)[0];
        return preg_replace('/\?|(\|?null\|?)/i', '', $rule);
    }
}