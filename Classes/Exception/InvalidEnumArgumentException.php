<?php

declare(strict_types=1);

namespace Iresults\Enum\Exception;

use Iresults\Enum\EnumInterface;

use function class_exists;
use function is_a;

class InvalidEnumArgumentException extends EnumException
{
    public static function assertValidEnumClass(string $className)
    {
        if (!class_exists($className) || !(is_a($className, EnumInterface::class, true))) {
            throw new InvalidEnumArgumentException('Argument must be a valid Enum implementation class name');
        }
    }
}
