<?php

namespace Iresults\Enum;

use Iresults\Enum\Exception\EnumException;
use Iresults\Enum\Exception\EnumOutOfRangeException;

interface EnumInterface
{
    /**
     * Enum constructor
     *
     * @param array|bool|float|int|string $value
     * @throws EnumException if the input is of an invalid type or it is neither a constant name nor a value
     */
    public function __construct($value);

    /**
     * Return the instance's value
     *
     * @return array|bool|float|int|string
     */
    public function getValue();

    /**
     * Return name associated with the instance's value
     *
     * @return string
     * @throws EnumOutOfRangeException if the constant doesn't exist
     */
    public function getName();
}
