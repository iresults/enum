<?php

declare(strict_types=1);

namespace Iresults\Enum;

use Iresults\Enum\Exception\EnumException;
use Iresults\Enum\Exception\EnumOutOfRangeException;

/**
 * @template T of scalar|null|list<scalar>
 */
interface EnumInterface
{
    /**
     * Return the instance of the given Enum value
     *
     * @param T $value
     *
     * @return static
     *
     * @throws EnumException if the input is of an invalid type or it is neither a constant name nor a value
     */
    public static function instance(
        array|bool|float|int|string|null $value,
    ): EnumInterface;

    /**
     * Return the instance's value
     *
     * @return T
     */
    public function getValue(): mixed;

    /**
     * Return name associated with the instance's value
     *
     * @throws EnumOutOfRangeException if the constant doesn't exist
     */
    public function getName(): string;
}
