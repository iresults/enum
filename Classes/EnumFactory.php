<?php
declare(strict_types=1);

namespace Iresults\Enum;

use InvalidArgumentException;
use Iresults\Enum\Exception\EnumOutOfRangeException;
use Iresults\Enum\Exception\InvalidEnumArgumentException;
use ReflectionClass;
use function array_key_exists;
use function array_search;
use function call_user_func;
use function get_class;
use function gettype;
use function is_array;
use function is_null;
use function is_object;
use function is_scalar;
use function is_string;
use function sprintf;
use function strtoupper;

abstract class EnumFactory
{
    private static $registry = [];

    private static $reflectionCache = [];

    /**
     * Return the instance of the enum from the registry or create a new one
     *
     * @param array|bool|float|int|string $valueOrName
     * @param string                      $className
     * @return EnumInterface
     */
    public static function makeInstance($valueOrName, string $className): EnumInterface
    {
        if (!static::isValidValueType($valueOrName)) {
            throw new InvalidEnumArgumentException(
                sprintf(
                    'Type of value is not a valid constant type or name: "%s"',
                    is_object($valueOrName) ? get_class($valueOrName) : gettype($valueOrName)
                )
            );
        }
        InvalidEnumArgumentException::assertValidEnumClass($className);

        if (is_scalar($valueOrName)) {
            $argumentRegistryKey = 'argkey::' . $className . '::' . gettype($valueOrName) . '::' . $valueOrName;
            if (isset(self::$registry[$argumentRegistryKey])) {
                return self::$registry[$argumentRegistryKey];
            }
        } else {
            $argumentRegistryKey = '';
        }

        if (is_string($valueOrName) && static::hasConstant($valueOrName, $className)) {
            $name = $valueOrName;
            $value = null;
        } else {
            $name = static::getNameForValueOfClass($valueOrName, $className);
            $value = $valueOrName;
        }

        if (false === $name) {
            throw new EnumOutOfRangeException(
                sprintf(
                    'Can not instantiate enum from input, because it is neither a constant '
                    . 'name nor a value of this enum. (%s)%s given',
                    gettype($valueOrName),
                    is_scalar($valueOrName) ? $valueOrName : ''
                )
            );
        }
        $registryKey = 'namekey::' . $className . '::' . strtoupper($name);
        if (!isset(self::$registry[$registryKey])) {
            $instance = call_user_func(
                [$className, 'createInstance'],
                $value ?? $value = static::retrieveValueForName($name, $className),
                $name
            );

            self::$registry[$registryKey] = $instance;
            if ($argumentRegistryKey) {
                self::$registry[$argumentRegistryKey] = $instance;
            }

            return $instance;
        }

        return self::$registry[$registryKey];
    }

    private static function getConstants(string $className)
    {
        if (!isset(static::$reflectionCache[$className])) {
            $reflection = new ReflectionClass($className);
            static::$reflectionCache[$className] = $reflection->getConstants();
        }

        return static::$reflectionCache[$className];
    }

    /**
     * Returns the if a constant with the given name exists
     *
     * @param string $constantName
     * @param string $className
     * @return bool
     */
    private static function hasConstant(string $constantName, string $className): bool
    {
        if (!is_string($constantName)) {
            throw new InvalidArgumentException('Expected argument "constantName" to be of type string');
        }

        return array_key_exists(strtoupper($constantName), static::getConstants($className));
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
    private static function getNameForValueOfClass($constantValue, string $className)
    {
        if (!static::isValidValueType($constantValue)) {
            return false;
        }

        static $descriptor = null;
        if (null === $descriptor) {
            $descriptor = new EnumDescriptor();
        }

        return array_search($constantValue, $descriptor->getValues($className), true);
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
    private static function retrieveValueForName(string $constantName, string $className)
    {
        return static::getConstants($className)[strtoupper($constantName)] ?? null;
    }
}
