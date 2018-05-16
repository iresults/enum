<?php

namespace Iresults\Enum;

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

    private function __construct($value, $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public static function instance($value)
    {
        return EnumFactory::makeInstance($value, get_called_class());
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName()
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
     * @return EnumInterface
     * @internal
     */
    public static function createInstance($value, $name)
    {
        return new static($value, $name);
    }

    /**
     * Returns the if a constant with the given name exists
     *
     * @param string $constantName
     * @return bool
     */
    public function hasConstant($constantName)
    {
        if (!is_string($constantName)) {
            throw new \InvalidArgumentException('Expected argument "constantName" to be of type string');
        }

        return defined(get_class($this) . '::' . strtoupper($constantName));
    }
}
