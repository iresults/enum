<?php

namespace Iresults\Enum\Tests\Unit;

use Iresults\Enum\Exception\EnumException;
use Iresults\Enum\Tests\Fixture\MixedEnum;

class MixedEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider getValueForNameDataProvider
     * @param $name
     * @param $expected
     */
    public function getValueForNameTest($name, $expected)
    {
        $this->assertSame($expected, (new MixedEnum($name))->getValue());
    }

    /**
     * @return array
     */
    public function getValueForNameDataProvider()
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
     * @dataProvider getNameForValueDataProvider
     * @param $value
     * @param $expected
     */
    public function getNameForValueTest($value, $expected)
    {
        $this->assertSame($expected, (new MixedEnum($value))->getName());
    }

    /**
     * @return array
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
     * @param $input
     * @param $expected
     * @dataProvider normalizeDataProvider
     */
    public function instanceCreationTest($input, $expected)
    {
        $enum = new MixedEnum($input);
        $this->assertEquals($expected, $enum->getValue());
    }

    /**
     * @return array
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
     * @expectedException \Iresults\Enum\Exception\EnumOutOfRangeException
     */
    public function instanceCreationShouldFailTest()
    {
        new MixedEnum('not in enum');
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
            new MixedEnum($value);
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
            [MixedEnum::COLLECTION, true],
            [MixedEnum::IS_FALSE, true],
            [MixedEnum::IS_TRUE, true],
            [MixedEnum::IS_NULL, true],
            [1000, false],
            ['', false],
        ];
    }
}
