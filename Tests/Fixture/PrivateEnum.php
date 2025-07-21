<?php

declare(strict_types=1);

namespace Iresults\Enum\Tests\Fixture;

use Iresults\Enum\Enum;

class PrivateEnum extends Enum
{
    private const COLLECTION = [1, 2, 3];
    private const IS_FALSE = false;
    private const IS_TRUE = true;
    private const IS_NULL = null;

    public static function collection()
    {
        return static::instance(static::COLLECTION);
    }

    public static function isFalse()
    {
        return static::instance(static::IS_FALSE);
    }

    public static function isTrue()
    {
        return static::instance(static::IS_TRUE);
    }

    public static function isNull()
    {
        return static::instance(static::IS_NULL);
    }
}
