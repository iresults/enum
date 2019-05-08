<?php
declare(strict_types=1);

namespace Iresults\Enum\Tests\Unit;

use Iresults\Enum\Exception\EnumException;
use Iresults\Enum\Tests\Fixture\AnimalEnum;
use PHPUnit\Framework\TestCase;

class SimpleEnumTest extends TestCase
{
    /**
     * @test
     * @dataProvider getValueForNameDataProvider
     * @param $name
     * @param $expected
     */
    public function getValueForNameTest($name, $expected)
    {
        $this->assertSame($expected, (AnimalEnum::instance($name))->getValue());
    }

    /**
     * @return array
     */
    public function getValueForNameDataProvider()
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
     * @dataProvider getNameForValueDataProvider
     * @param $value
     * @param $expected
     */
    public function getNameForValueTest($value, $expected)
    {
        $this->assertSame($expected, (AnimalEnum::instance($value))->getName());
    }

    /**
     * @return array
     */
    public function getNameForValueDataProvider()
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
     * @param $input
     * @param $expected
     * @dataProvider normalizeDataProvider
     */
    public function instanceCreationTest($input, $expected)
    {
        $enum = AnimalEnum::instance($input);
        $this->assertEquals($expected, $enum->getValue());
    }

    /**
     * @test
     * @param $input
     * @dataProvider normalizeDataProvider
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
     * @expectedException \Iresults\Enum\Exception\EnumOutOfRangeException
     */
    public function instanceCreationShouldFailTest()
    {
        AnimalEnum::instance('not in enum');
    }

    /**
     * @test
     * @param $left
     * @param $right
     * @dataProvider instanceComparisonFalseDataProvider
     */
    public function instanceComparisonFalseTest($left, $right)
    {
        $this->assertNotEquals(AnimalEnum::instance($left), AnimalEnum::instance($right));
        $this->assertNotSame(AnimalEnum::instance($left), AnimalEnum::instance($right));
        $this->assertTrue(AnimalEnum::instance($left) != AnimalEnum::instance($right));
        $this->assertTrue(AnimalEnum::instance($left) !== AnimalEnum::instance($right));
    }

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
     * @dataProvider isValidValueDataProvider
     * @param $value
     * @param $expected
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
