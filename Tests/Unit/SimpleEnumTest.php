<?php

declare(strict_types=1);

namespace Iresults\Enum\Tests\Unit;

use Iresults\Enum\Exception\EnumException;
use Iresults\Enum\Exception\EnumOutOfRangeException;
use Iresults\Enum\Tests\Fixture\AnimalEnum;
use PHPUnit\Framework\TestCase;

class SimpleEnumTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getValueForNameDataProvider
     */
    public function getValueForNameTest(string $name, int $expected): void
    {
        $this->assertSame($expected, (AnimalEnum::instance($name))->getValue());
    }

    /**
     * @return list<array{0:string,1:int}>
     */
    public static function getValueForNameDataProvider()
    {
        return [
            ['CAT', AnimalEnum::CAT],
            ['cat', AnimalEnum::CAT],
            ['DOG', AnimalEnum::DOG],
            ['dog', AnimalEnum::DOG],
            ['BIRD', AnimalEnum::BIRD],
            ['bird', AnimalEnum::BIRD],
            ['RODENT', AnimalEnum::RODENT],
            ['rodent', AnimalEnum::RODENT],
        ];
    }

    /**
     * @test
     *
     * @dataProvider getNameForValueDataProvider
     *
     * @return void
     */
    public function getNameForValueTest(int $value, string $expected)
    {
        $this->assertSame($expected, (AnimalEnum::instance($value))->getName());
    }

    /**
     * @return list<array{0:int,1:string}>
     */
    public static function getNameForValueDataProvider()
    {
        return [
            [AnimalEnum::CAT, 'CAT'],
            [AnimalEnum::DOG, 'DOG'],
            [AnimalEnum::BIRD, 'BIRD'],
            [AnimalEnum::RODENT, 'RODENT'],
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
        $enum = AnimalEnum::instance($input);
        $this->assertEquals($expected, $enum->getValue());
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
        $this->assertEquals(AnimalEnum::instance($input), AnimalEnum::instance($input));
        $this->assertSame(AnimalEnum::instance($input), AnimalEnum::instance($input));
        $this->assertTrue(AnimalEnum::instance($input) == AnimalEnum::instance($input));
        $this->assertTrue(AnimalEnum::instance($input) === AnimalEnum::instance($input));
    }

    /**
     * @return array
     */
    public function normalizeDataProvider()
    {
        return [
            ['CAT', AnimalEnum::CAT],
            ['cat', AnimalEnum::CAT],
            [AnimalEnum::CAT, AnimalEnum::CAT],
            ['DOG', AnimalEnum::DOG],
            ['dog', AnimalEnum::DOG],
            [AnimalEnum::DOG, AnimalEnum::DOG],
            ['BIRD', AnimalEnum::BIRD],
            ['bird', AnimalEnum::BIRD],
            [AnimalEnum::BIRD, AnimalEnum::BIRD],
            ['RODENT', AnimalEnum::RODENT],
            ['rodent', AnimalEnum::RODENT],
            [AnimalEnum::RODENT, AnimalEnum::RODENT],
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
        AnimalEnum::instance('not in enum');
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
        $this->assertNotEquals(AnimalEnum::instance($left), AnimalEnum::instance($right));
        $this->assertNotSame(AnimalEnum::instance($left), AnimalEnum::instance($right));
        $this->assertTrue(AnimalEnum::instance($left) != AnimalEnum::instance($right));
        $this->assertTrue(AnimalEnum::instance($left) !== AnimalEnum::instance($right));
    }

    /**
     * @return array<int,mixed>
     */
    public function instanceComparisonFalseDataProvider()
    {
        return [
            ['BIRD', AnimalEnum::CAT],
            ['bird', AnimalEnum::CAT],
            [AnimalEnum::BIRD, AnimalEnum::CAT],
            ['CAT', AnimalEnum::DOG],
            ['cat', AnimalEnum::DOG],
            [AnimalEnum::CAT, AnimalEnum::DOG],
            ['RODENT', AnimalEnum::BIRD],
            ['rodent', AnimalEnum::BIRD],
            [AnimalEnum::RODENT, AnimalEnum::BIRD],
            ['DOG', AnimalEnum::RODENT],
            ['dog', AnimalEnum::RODENT],
            [AnimalEnum::DOG, AnimalEnum::RODENT],
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
            AnimalEnum::instance($value);
            $actual = true;
        } catch (EnumException $e) {
            $actual = false;
        }
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public function isValidValueDataProvider()
    {
        return [
            [AnimalEnum::CAT, true],
            [AnimalEnum::DOG, true],
            [AnimalEnum::BIRD, true],
            [AnimalEnum::RODENT, true],
            [1000, false],
            ['', false],
        ];
    }
}
