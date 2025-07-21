<?php

declare(strict_types=1);

namespace Iresults\Enum;

use Iresults\Enum\Exception\EnumOutOfRangeException;
use Iresults\Enum\Exception\InvalidEnumArgumentException;
use ReflectionClass;

use function array_key_exists;
use function array_search;
use function gettype;
use function is_null;
use function is_scalar;
use function is_string;
use function sprintf;
use function strtoupper;

final class EnumFactory
{
    /**
     * @var array<string, EnumInterface<scalar|list<scalar>|null>>
     */
    private static array $registry = [];

    /**
     * @var array<class-string, array<string,mixed>>
     */
    private static array $reflectionCache = [];

    private function __construct()
    {
    }

    /**
     * Return the instance of the enum from the registry or create a new one
     *
     * @template T of scalar|null|list<scalar>
     *
     * @param T|string                       $valueOrName
     * @param class-string<EnumInterface<T>> $className
     *
     * @return EnumInterface<T>
     */
    public static function makeInstance(
        array|bool|float|int|string|null $valueOrName,
        string $className,
    ): EnumInterface {
        if (!static::isValidValueType($valueOrName)) {
            throw new InvalidEnumArgumentException(sprintf('Type of value is not a valid constant type or name: "%s"', get_debug_type($valueOrName)));
        }
        InvalidEnumArgumentException::assertValidEnumClass($className);

        if (is_scalar($valueOrName)) {
            $argumentRegistryKey = 'argkey::' . $className . '::' . gettype($valueOrName) . '::' . $valueOrName;
            if (isset(self::$registry[$argumentRegistryKey])) {
                // @phpstan-ignore return.type
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
            throw new EnumOutOfRangeException(sprintf('Can not instantiate enum from input, because it is neither a constant name nor a value of this enum. (%s)%s given', gettype($valueOrName), is_scalar($valueOrName) ? $valueOrName : ''));
        }
        $registryKey = 'namekey::' . $className . '::' . strtoupper($name);
        if (!isset(self::$registry[$registryKey])) {
            // @phpstan-ignore staticMethod.notFound
            $instance = $className::createInstance(
                $value ?? $value = static::retrieveValueForName($name, $className),
                $name
            );

            self::$registry[$registryKey] = $instance;
            if ($argumentRegistryKey) {
                self::$registry[$argumentRegistryKey] = $instance;
            }

            return $instance;
        }

        // @phpstan-ignore return.type
        return self::$registry[$registryKey];
    }

    /**
     * @return array<string,mixed>
     */
    private static function getConstants(string $className): array
    {
        if (!isset(static::$reflectionCache[$className])) {
            $reflection = new ReflectionClass($className);
            static::$reflectionCache[$className] = $reflection->getConstants();
        }

        return static::$reflectionCache[$className];
    }

    /**
     * Returns the if a constant with the given name exists
     */
    private static function hasConstant(
        string $constantName,
        string $className,
    ): bool {
        return array_key_exists(
            strtoupper($constantName),
            static::getConstants($className)
        );
    }

    /**
     * Return the constant name for the given value
     *
     * If the enum contains multiple constants with the given value the behaviour is undefined
     *
     * @param mixed[]|bool|float|int|string $constantValue
     *
     * @return string|bool Returns the name or FALSE if not found
     */
    private static function getNameForValueOfClass(
        array|bool|float|int|string|null $constantValue,
        string $className,
    ): bool|int|string {
        if (!static::isValidValueType($constantValue)) {
            return false;
        }

        static $descriptor = null;
        if (null === $descriptor) {
            $descriptor = new EnumDescriptor();
        }

        return array_search(
            $constantValue,
            $descriptor->getValues($className),
            true
        );
    }

    /**
     * Return if the given value is a valid type for an enum in general
     *
     * @param mixed[]|bool|float|int|string|null $value
     */
    private static function isValidValueType(array|bool|float|int|string|null $value): bool
    {
        if (is_null($value) || is_scalar($value)) {
            return true;
        }

        // Loop through the elements of the array and return false if one of it is not a valid value type
        foreach ($value as $element) {
            if (!static::isValidValueType($element)) {
                return false;
            }
        }

        return true;
    }

    private static function retrieveValueForName(
        string $constantName,
        string $className,
    ): mixed {
        return static::getConstants($className)[strtoupper($constantName)] ?? null;
    }
}
