<?php
declare(strict_types=1);

namespace Iresults\Enum;

use Iresults\Enum\Exception\InvalidEnumArgumentException;
use ReflectionClass;
use function array_keys;

class EnumDescriptor
{
    private $constantsCache = [];

    /**
     * Return a dictionary of the constant values of the given Enum class
     *
     * @param string $className
     * @return array
     */
    public function getValues(string $className): array
    {
        InvalidEnumArgumentException::assertValidEnumClass($className);
        if (!isset($this->constantsCache[$className])) {
            $reflection = new ReflectionClass($className);
            $this->constantsCache[$className] = $reflection->getConstants();
        }

        return $this->constantsCache[$className];
    }

    /**
     * Return an array of the constant names of the given Enum class
     *
     * @param string $className
     * @return array
     */
    public function getNames(string $className): array
    {
        return array_keys($this->getValues($className));
    }
}
