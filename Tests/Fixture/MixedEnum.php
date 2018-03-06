<?php

namespace Iresults\Enum\Tests\Fixture;

use Iresults\Enum\Enum;

class MixedEnum extends Enum
{
    const COLLECTION = [1, 2];
    const IS_FALSE = false;
    const IS_TRUE = true;
    const IS_NULL = null;
}
