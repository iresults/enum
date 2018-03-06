<?php

namespace Iresults\Enum;

use Iresults\Enum\Exception\EnumOutOfRangeException;
use Iresults\Enum\Exception\InvalidEnumArgumentException;

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

    public function __construct($value)
    {
        if (!$this->isValidValueType($value)) {
            throw new InvalidEnumArgumentException(
                sprintf('Type of value is not a valid constant type or name: "%s"', gettype($value))
            );
        }

        if (is_string($value) && $this->hasConstant($value)) {
            $this->value = $this->retrieveValueForName($value);
        } elseif ($this->isValidValue($value)) {
            $this->value = $value;
        } else {
            throw new EnumOutOfRangeException(
                'Can not instantiate enum from input, because it is neither a constant name nor a value of this enum'
            );
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName()
    {
        if (!$this->name) {
            $this->name = $this->retrieveName();
        }

        return $this->name;
    }

    /**
     * Returns the if a constant with the given name exists
     *
     * @param string $constantName
     * @return bool
     */
    private function hasConstant($constantName)
    {
        if (!is_string($constantName)) {
            throw new \InvalidArgumentException('Expected argument "constantName" to be of type string');
        }

        return defined(get_class($this) . '::' . strtoupper($constantName));
    }

    /**
     * Returns the constant name for the given value
     *
     * If the enum contains multiple constants with the given value the behaviour is undefined
     *
     * @param int|float|string|array|bool $constantValue
     * @return string|bool Returns the name or FALSE if not found
     */
    private function getNameForValue($constantValue)
    {
        if (!$this->isValidValueType($constantValue)) {
            return false;
        }
        try {
            $reflection = new \ReflectionClass(get_class($this));
        } catch (\ReflectionException $e) {
            return false;
        }

        return array_search($constantValue, $reflection->getConstants(), true);
    }

    /**
     * Returns if the given value is contained within the enum
     *
     * @param int|float|string|array|bool $value
     * @return string|bool Returns the name or FALSE if not found
     */
    private function isValidValue($value)
    {
        return false !== $this->getNameForValue($value);
    }

    /**
     * Returns if the given value is a valid type for an enum in general
     *
     * @param mixed $value
     * @return bool
     */
    private function isValidValueType($value)
    {
        if (is_null($value) || is_scalar($value)) {
            return true;
        }
        if (is_array($value)) {
            // Loop through the elements of the array and return false if one of it is not a valid value type
            foreach ($value as $element) {
                if (!$this->isValidValueType($element)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param $constantName
     * @return mixed
     */
    private function retrieveValueForName($constantName)
    {
        return constant(get_class($this) . '::' . strtoupper($constantName));
    }

    /**
     * @return bool|false|int|string
     */
    private function retrieveName()
    {
        try {
            $reflection = new \ReflectionClass(get_class($this));
        } catch (\ReflectionException $e) {
            return false;
        }

        $result = array_search($this->value, $reflection->getConstants(), true);
        if (false === $result) {
            throw new EnumOutOfRangeException('Enum instance has been created with an invalid value');
        }

        return $result;
    }
}
