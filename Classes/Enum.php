<?php
declare(strict_types=1);

namespace Iresults\Enum;

use InvalidArgumentException;
use Iresults\Enum\Exception\EnumException;

abstract class Enum implements EnumInterface
{
    /**
     * @var int|float|string|array|bool
     */
    private $value;

    /**
     * @var string
     */
    private $name = '';

    protected function __construct($value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    /**
     * Return the instance of the given Enum
     *
     * @param array|bool|float|int|string $value
     * @return static
     * @throws EnumException if the input is of an invalid type or it is neither a constant name nor a value
     */
    public static function instance($value): EnumInterface
    {
        return EnumFactory::makeInstance($value, get_called_class());
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Create a new instance of the Enum
     *
     * This method is only meant to be called from the Enum Factory
     *
     * @param array|bool|float|int|string $value
     * @param string                      $name
     * @return static
     * @internal
     */
    public static function createInstance($value, string $name): EnumInterface
    {
        return new static($value, $name);
    }

    /**
     * Returns the if a constant with the given name exists
     *
     * @param string $constantName
     * @return bool
     */
    public function hasConstant(string $constantName): bool
    {
        if (!is_string($constantName)) {
            throw new InvalidArgumentException('Expected argument "constantName" to be of type string');
        }

        return defined(get_class($this) . '::' . strtoupper($constantName));
    }

    /**
     * Enum instances should not be serialized or you MUST NOT relay on object equality
     *
     * @deprecated
     */
    public function __wakeup()
    {
    }
}
