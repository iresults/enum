<?php

declare(strict_types=1);

namespace Iresults\Enum\Tests\Fixture;

use Iresults\Enum\Enum;

class MixedEnum extends Enum
{
    public const COLLECTION = [1, 2];
    public const IS_FALSE = false;
    public const IS_TRUE = true;
    public const IS_NULL = null;
}
