<?php

declare(strict_types=1);

namespace Iresults\Enum\Tests\Fixture;

use Iresults\Enum\Enum;

class AnimalEnum extends Enum
{
    public const CAT = 1;
    public const DOG = 2;
    public const BIRD = 3;
    public const RODENT = 4;
}
