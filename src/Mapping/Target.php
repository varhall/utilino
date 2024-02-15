<?php

namespace Varhall\Utilino\Mapping;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\Utils\Type;
use Varhall\Utilino\Mapping\Attributes\Date;
use Varhall\Utilino\Mapping\Attributes\IBase;
use Varhall\Utilino\Mapping\Attributes\IModifier;
use Varhall\Utilino\Mapping\Attributes\Rule;
use Varhall\Utilino\Mapping\Attributes\Structure;

/**
 * @method string getName()
 * @method \ReflectionNamedType getType()
 * @method bool hasType()
 * @method \ReflectionAttribute[] getAttributes(string $type = null)
 */
class Target
{
    protected array $mappers;

    protected \ReflectionProperty|\ReflectionParameter $object;

    public function __construct(\ReflectionProperty|\ReflectionParameter $object)
    {
        $this->object = $object;
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([ $this->object, $name ], $arguments);
    }

    public function schema(): Schema
    {
        $type = $this->type();
        $schema = $this->base()->schema();

        if ($type && count($this->type()->getTypes()) === 1) {
            $schema = $schema->castTo($this->object->getType()->getName());
        }

        if (!$type || $this->object->getType()->allowsNull()) {
            $schema = Expect::anyOf($schema, 'null', 'nil')
                        ->nullable()
                        ->before(fn($value) => $value === 'null' || $value === 'nil' ? null : $value);
        }

        return $this->modifySchema($schema);
    }

    protected function base(): IBase
    {
        $rules = $this->object->getAttributes(IBase::class, \ReflectionAttribute::IS_INSTANCEOF);

        if (count($rules) > 1) {
            throw new \Nette\InvalidStateException('Multiple rules found for ' . $this->object->getName());
        }

        $attribute = array_shift($rules);

        if ($attribute) {
            return $attribute->newInstance();

        } else if ($this->isDateType()) {
            return new Date();

        } else if ($this->type()?->isClass()) {
            return new Structure($this->object->getType()->getName());

        } else {
            return new Rule($this->object->getType()?->getName() ?? 'mixed');
        }
    }

    protected function type(): ?Type
    {
        return $this->object->hasType()
            ? Type::fromString($this->object->getType()->getName())
            : null;
    }

    protected function isDateType(): bool
    {
        if (!$this->object->hasType()) {
            return false;
        }

        $type = $this->object->getType()->getName();
        return class_exists($type) && ($type === \DateTime::class || is_subclass_of($type, \DateTime::class));
    }

    protected function modifySchema(Schema $schema): Schema
    {
        $rules = $this->object->getAttributes(IModifier::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($rules as $rule) {
            $schema = $rule->newInstance()->modify($schema);
        }

        return $schema;
    }
}