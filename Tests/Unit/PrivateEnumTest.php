<?php

declare(strict_types=1);

namespace Iresults\Enum\Tests\Unit;

use Iresults\Enum\EnumInterface;
use Iresults\Enum\Exception\EnumException;
use Iresults\Enum\Exception\EnumOutOfRangeException;
use Iresults\Enum\Tests\Fixture\PrivateEnum;
use PHPUnit\Framework\TestCase;

class PrivateEnumTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function instanceAreTheSameTest()
    {
        $this->assertSame(PrivateEnum::collection(), PrivateEnum::instance([1, 2, 3]));
        $this->assertSame(PrivateEnum::collection(), PrivateEnum::instance('COLLECTION'));
        $this->assertSame(PrivateEnum::collection(), PrivateEnum::instance('collection'));

        $this->assertSame(PrivateEnum::isFalse(), PrivateEnum::instance(false));
        $this->assertSame(PrivateEnum::isFalse(), PrivateEnum::instance('IS_FALSE'));
        $this->assertSame(PrivateEnum::isFalse(), PrivateEnum::instance('is_false'));

        $this->assertSame(PrivateEnum::isTrue(), PrivateEnum::instance(true));
        $this->assertSame(PrivateEnum::isTrue(), PrivateEnum::instance('IS_TRUE'));
        $this->assertSame(PrivateEnum::isTrue(), PrivateEnum::instance('is_true'));

        $this->assertSame(PrivateEnum::isNull(), PrivateEnum::instance(null));
        $this->assertSame(PrivateEnum::isNull(), PrivateEnum::instance('IS_NULL'));
        $this->assertSame(PrivateEnum::isNull(), PrivateEnum::instance('is_null'));
    }

    /**
     * @test
     *
     * @dataProvider instanceDataProvider
     *
     * @param EnumInterface $expected
     *
     * @return void
     */
    public function instanceTest($name, $expected)
    {
        $this->assertEquals($expected, PrivateEnum::instance($name));
    }

    /**
     * @return array<string, array{0:string, 1:PrivateEnum}>
     */
    public static function instanceDataProvider()
    {
        return [
            'COLLECTION' => ['COLLECTION', PrivateEnum::collection()],
            'Collection' => ['Collection', PrivateEnum::collection()],
            'IS_FALSE'   => ['IS_FALSE', PrivateEnum::isFalse()],
            'is_false'   => ['is_false', PrivateEnum::isFalse()],
            'IS_TRUE'    => ['IS_TRUE', PrivateEnum::isTrue()],
            'is_true'    => ['is_true', PrivateEnum::isTrue()],
            'IS_NULL'    => ['IS_NULL', PrivateEnum::isNull()],
            'is_null'    => ['is_null', PrivateEnum::isNull()],
        ];
    }

    /**
     * @test
     *
     * @dataProvider getNameForValueDataProvider
     *
     * @return void
     */
    public function getNameForValueTest($value, $expected)
    {
        $this->assertSame($expected, (PrivateEnum::instance($value))->getName());
    }

    /**
     * @return list<array{0:mixed, 1:string}>
     */
    public static function getNameForValueDataProvider()
    {
        return [
            [[1, 2, 3], 'COLLECTION'],
            [false, 'IS_FALSE'],
            [true, 'IS_TRUE'],
            [null, 'IS_NULL'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider normalizeDataProvider
     *
     * @return void
     */
    public function instanceCreationTest($input, $expected)
    {
        $enum = PrivateEnum::instance($input);
        $this->assertEquals($expected, $enum);
    }

    /**
     * @return list<array{0:mixed, 1:PrivateEnum}>
     */
    public static function normalizeDataProvider()
    {
        return [
            ['COLLECTION', PrivateEnum::collection()],
            ['collection', PrivateEnum::collection()],
            [[1, 2, 3], PrivateEnum::collection()],
            ['IS_FALSE', PrivateEnum::isFalse()],
            ['is_false', PrivateEnum::isFalse()],
            [false, PrivateEnum::isFalse()],
            ['IS_TRUE', PrivateEnum::isTrue()],
            ['is_true', PrivateEnum::isTrue()],
            [true, PrivateEnum::isTrue()],
            ['IS_NULL', PrivateEnum::isNull()],
            ['is_null', PrivateEnum::isNull()],
            [null, PrivateEnum::isNull()],
        ];
    }

    /**
     * @test
     *
     * @return void
     */
    public function instanceCreationShouldFailTest()
    {
        $this->expectException(EnumOutOfRangeException::class);
        PrivateEnum::instance('not in enum');
    }

    /**
     * @test
     *
     * @dataProvider normalizeDataProvider
     *
     * @return void
     */
    public function instanceComparisonTest($input)
    {
        $this->assertEquals(PrivateEnum::instance($input), PrivateEnum::instance($input));
        $this->assertSame(PrivateEnum::instance($input), PrivateEnum::instance($input));
        /* @noinspection PhpNonStrictObjectEqualityInspection */
        $this->assertTrue(PrivateEnum::instance($input) == PrivateEnum::instance($input));
        $this->assertTrue(PrivateEnum::instance($input) === PrivateEnum::instance($input));
    }

    /**
     * @test
     *
     * @dataProvider instanceComparisonFalseDataProvider
     *
     * @return void
     */
    public function instanceComparisonFalseTest($left, $right)
    {
        $this->assertNotEquals(PrivateEnum::instance($left), PrivateEnum::instance($right));
        $this->assertNotSame(PrivateEnum::instance($left), PrivateEnum::instance($right));
        /* @noinspection PhpNonStrictObjectEqualityInspection */
        $this->assertTrue(PrivateEnum::instance($left) != PrivateEnum::instance($right));
        $this->assertTrue(PrivateEnum::instance($left) !== PrivateEnum::instance($right));
    }

    /**
     * @return list<array{0:mixed, 1:mixed}>
     */
    public function instanceComparisonFalseDataProvider()
    {
        return [
            ['COLLECTION', false],
            ['collection', false],
            [[1, 2, 3], false],
            ['IS_FALSE', [1, 2, 3]],
            ['is_false', [1, 2, 3]],
            [false, [1, 2, 3]],
            ['IS_TRUE', null],
            ['is_true', null],
            [true, null],
            ['IS_NULL', true],
            ['is_null', true],
            [null, true],
        ];
    }

    /**
     * @test
     *
     * @dataProvider isValidValueDataProvider
     *
     * @return void
     */
    public function isValidValueTest($value, $expected)
    {
        try {
            PrivateEnum::instance($value);
            $actual = true;
        } catch (EnumException $e) {
            $actual = false;
        }
        $this->assertSame($expected, $actual);
    }

    /**
     * @return list<array{0:mixed, 1:bool}>
     */
    public static function isValidValueDataProvider()
    {
        return [
            [[1, 2, 3], true],
            [false, true],
            [true, true],
            [null, true],
            [1000, false],
            ['', false],
        ];
    }
}
