<?php

declare(strict_types=1);

namespace Iresults\Enum\Tests\Unit;

use Iresults\Enum\Exception\EnumException;
use Iresults\Enum\Exception\EnumOutOfRangeException;
use Iresults\Enum\Tests\Fixture\MixedEnum;
use PHPUnit\Framework\TestCase;

class MixedEnumTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getValueForNameDataProvider
     */
    public function getValueForNameTest($name, $expected)
    {
        $this->assertSame($expected, (MixedEnum::instance($name))->getValue());
    }

    /**
     * @return list<array{0:string, 1:mixed}>
     */
    public static function getValueForNameDataProvider()
    {
        return [
            ['COLLECTION', MixedEnum::COLLECTION],
            ['collection', MixedEnum::COLLECTION],
            ['IS_FALSE', MixedEnum::IS_FALSE],
            ['is_false', MixedEnum::IS_FALSE],
            ['IS_TRUE', MixedEnum::IS_TRUE],
            ['is_true', MixedEnum::IS_TRUE],
            ['IS_NULL', MixedEnum::IS_NULL],
            ['is_null', MixedEnum::IS_NULL],
        ];
    }

    /**
     * @test
     *
     * @dataProvider getNameForValueDataProvider
     */
    public function getNameForValueTest($value, $expected)
    {
        $this->assertSame($expected, (MixedEnum::instance($value))->getName());
    }

    /**
     * @return list<array{0:mixed, 1:string}>
     */
    public function getNameForValueDataProvider()
    {
        return [
            [MixedEnum::COLLECTION, 'COLLECTION'],
            [MixedEnum::IS_FALSE, 'IS_FALSE'],
            [MixedEnum::IS_TRUE, 'IS_TRUE'],
            [MixedEnum::IS_NULL, 'IS_NULL'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider normalizeDataProvider
     */
    public function instanceCreationTest($input, $expected)
    {
        $enum = MixedEnum::instance($input);
        $this->assertEquals($expected, $enum->getValue());
    }

    /**
     * @return list<array{0:mixed, 1:mixed}>
     */
    public function normalizeDataProvider()
    {
        return [
            ['COLLECTION', MixedEnum::COLLECTION],
            ['collection', MixedEnum::COLLECTION],
            [MixedEnum::COLLECTION, MixedEnum::COLLECTION],
            ['IS_FALSE', MixedEnum::IS_FALSE],
            ['is_false', MixedEnum::IS_FALSE],
            [MixedEnum::IS_FALSE, MixedEnum::IS_FALSE],
            ['IS_TRUE', MixedEnum::IS_TRUE],
            ['is_true', MixedEnum::IS_TRUE],
            [MixedEnum::IS_TRUE, MixedEnum::IS_TRUE],
            ['IS_NULL', MixedEnum::IS_NULL],
            ['is_null', MixedEnum::IS_NULL],
            [MixedEnum::IS_NULL, MixedEnum::IS_NULL],
        ];
    }

    /**
     * @test
     */
    public function instanceCreationShouldFailTest()
    {
        $this->expectException(EnumOutOfRangeException::class);
        MixedEnum::instance('not in enum');
    }

    /**
     * @test
     *
     * @dataProvider normalizeDataProvider
     */
    public function instanceComparisonTest($input)
    {
        $this->assertEquals(MixedEnum::instance($input), MixedEnum::instance($input));
        $this->assertSame(MixedEnum::instance($input), MixedEnum::instance($input));
        /* @noinspection PhpNonStrictObjectEqualityInspection */
        $this->assertTrue(MixedEnum::instance($input) == MixedEnum::instance($input));
        $this->assertTrue(MixedEnum::instance($input) === MixedEnum::instance($input));
    }

    /**
     * @test
     *
     * @dataProvider instanceComparisonFalseDataProvider
     */
    public function instanceComparisonFalseTest($left, $right)
    {
        $this->assertNotEquals(MixedEnum::instance($left), MixedEnum::instance($right));
        $this->assertNotSame(MixedEnum::instance($left), MixedEnum::instance($right));
        /* @noinspection PhpNonStrictObjectEqualityInspection */
        $this->assertTrue(MixedEnum::instance($left) != MixedEnum::instance($right));
        $this->assertTrue(MixedEnum::instance($left) !== MixedEnum::instance($right));
    }

    /**
     * @return list<array{0:mixed, 1:mixed}>
     */
    public function instanceComparisonFalseDataProvider()
    {
        return [
            ['COLLECTION', MixedEnum::IS_FALSE],
            ['collection', MixedEnum::IS_FALSE],
            [MixedEnum::COLLECTION, MixedEnum::IS_FALSE],
            ['IS_FALSE', MixedEnum::COLLECTION],
            ['is_false', MixedEnum::COLLECTION],
            [MixedEnum::IS_FALSE, MixedEnum::COLLECTION],
            ['IS_TRUE', MixedEnum::IS_NULL],
            ['is_true', MixedEnum::IS_NULL],
            [MixedEnum::IS_TRUE, MixedEnum::IS_NULL],
            ['IS_NULL', MixedEnum::IS_TRUE],
            ['is_null', MixedEnum::IS_TRUE],
            [MixedEnum::IS_NULL, MixedEnum::IS_TRUE],
        ];
    }

    /**
     * @test
     *
     * @dataProvider isValidValueDataProvider
     */
    public function isValidValueTest($value, $expected)
    {
        try {
            MixedEnum::instance($value);
            $actual = true;
        } catch (EnumException $e) {
            $actual = false;
        }
        $this->assertSame($expected, $actual);
    }

    /**
     * @return list<array{0:mixed, 1:mixed}>
     */
    public function isValidValueDataProvider()
    {
        return [
            [MixedEnum::COLLECTION, true],
            [MixedEnum::IS_FALSE, true],
            [MixedEnum::IS_TRUE, true],
            [MixedEnum::IS_NULL, true],
            [1000, false],
            ['', false],
        ];
    }
}
