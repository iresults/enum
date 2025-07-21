<?php

declare(strict_types=1);

namespace Iresults\Enum;

use Iresults\Enum\Exception\EnumException;

/**
 * @template T of scalar|null|list<scalar>
 *
 * @implements EnumInterface<T>
 */
abstract class Enum implements EnumInterface
{
    /**
     * @param T $value
     */
    final protected function __construct(
        public readonly mixed $value,
        public readonly string $name,
    ) {
    }

    /**
     * Return the instance of the given Enum
     *
     * @return static
     *
     * @throws EnumException if the input is of an invalid type or it is neither a constant name nor a value
     */
    public static function instance(
        array|bool|float|int|string|null $value,
    ): EnumInterface {
        // @phpstan-ignore return.type
        return EnumFactory::makeInstance($value, get_called_class());
    }

    /**
     * @return T
     *
     * @deprecated use value property instead
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @deprecated use name property instead
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Create a new instance of the Enum
     *
     * This method is only meant to be called from the Enum Factory
     *
     * @param mixed[]|bool|float|int|string|null $value
     *
     * @return EnumInterface<T>
     *
     * @internal
     */
    public static function createInstance(
        array|bool|float|int|string|null $value,
        string $name,
    ): EnumInterface {
        return new static($value, $name);
    }

    /**
     * Return the if a constant with the given name exists
     */
    public function hasConstant(string $constantName): bool
    {
        return defined(get_class($this) . '::' . strtoupper($constantName));
    }

    /**
     * Enum instances should not be serialized or you MUST NOT relay on object equality
     *
     * @deprecated
     */
    public function __wakeup(): void
    {
    }
}
