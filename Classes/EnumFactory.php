<?php

namespace Iresults\Enum;

use Iresults\Enum\Exception\EnumOutOfRangeException;
use Iresults\Enum\Exception\InvalidEnumArgumentException;

abstract class EnumFactory
{
    private static $registry = [];

    /**
     * Return the instance of the enum from the registry or create a new one
     *
     * @param array|bool|float|int|string $valueOrName
     * @param string                      $className
     * @return EnumInterface
     */
    public static function makeInstance($valueOrName, $className)
    {
        if (!static::isValidValueType($valueOrName)) {
            throw new InvalidEnumArgumentException(
                sprintf('Type of value is not a valid constant type or name: "%s"', $valueOrName)
            );
        }
        if (!is_string($className) || !(is_a($className, Enum::class, true))) {
            throw new InvalidEnumArgumentException('Argument "className" must be a valid class name');
        }
        if (is_string($valueOrName) && static::hasConstant($valueOrName, $className)) {
            $name = $valueOrName;
            $value = static::retrieveValueForName($valueOrName, $className);
        } else {
            $name = static::getNameForValueOfClass($valueOrName, $className);
            $value = $valueOrName;
        }

        if (false === $name) {
            throw new EnumOutOfRangeException(
                'Can not instantiate enum from input, because it is neither a constant name nor a value of this enum'
            );
        }
        $registryKey = $className . '::' . $name;

        if (!isset(self::$registry[$registryKey])) {
            self::$registry[$registryKey] = call_user_func([$className, 'createInstance'], $value, $name);
        }

        return self::$registry[$registryKey];
    }

    /**
     * Returns the if a constant with the given name exists
     *
     * @param string $constantName
     * @param string $className
     * @return bool
     */
    private static function hasConstant($constantName, $className)
    {
        if (!is_string($constantName)) {
            throw new \InvalidArgumentException('Expected argument "constantName" to be of type string');
        }

        return defined($className . '::' . strtoupper($constantName));
    }

    /**
     * Returns the constant name for the given value
     *
     * If the enum contains multiple constants with the given value the behaviour is undefined
     *
     * @param int|float|string|array|bool $constantValue
     * @param string                      $className
     * @return string|bool Returns the name or FALSE if not found
     */
    private static function getNameForValueOfClass($constantValue, $className)
    {
        if (!static::isValidValueType($constantValue)) {
            return false;
        }
        try {
            $reflection = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            return false;
        }

        return array_search($constantValue, $reflection->getConstants(), true);
    }

    /**
     * Returns if the given value is a valid type for an enum in general
     *
     * @param mixed $value
     * @return bool
     */
    private static function isValidValueType($value)
    {
        if (is_null($value) || is_scalar($value)) {
            return true;
        }
        if (is_array($value)) {
            // Loop through the elements of the array and return false if one of it is not a valid value type
            foreach ($value as $element) {
                if (!static::isValidValueType($element)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $constantName
     * @param string $className
     * @return mixed
     */
    private static function retrieveValueForName($constantName, $className)
    {
        return constant($className . '::' . strtoupper($constantName));
    }
}
