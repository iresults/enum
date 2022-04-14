<?php
declare(strict_types=1);

namespace Iresults\Enum\Tests\Unit;

use Iresults\Enum\EnumDescriptor;
use Iresults\Enum\Tests\Fixture\AnimalEnum;
use Iresults\Enum\Tests\Fixture\EmptyEnum;
use Iresults\Enum\Tests\Fixture\MixedEnum;
use PHPUnit\Framework\TestCase;

class EnumDescriptorTest extends TestCase
{
    /**
     * @var EnumDescriptor
     */
    private $fixture;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixture = new EnumDescriptor();
    }

    protected function tearDown(): void
    {
        unset($this->fixture);
        parent::tearDown();
    }

    /**
     * @dataProvider getValuesDataProvider
     * @param string $class
     * @param array  $expected
     */
    public function testGetValues(string $class, array $expected)
    {
        $this->assertSame(
            $expected,
            $this->fixture->getValues($class)
        );
    }

    public function getValuesDataProvider()
    {
        return [
            [
                EmptyEnum::class,
                [],
            ],
            [
                AnimalEnum::class,
                [
                    'CAT'    => AnimalEnum::CAT,
                    'DOG'    => AnimalEnum::DOG,
                    'BIRD'   => AnimalEnum::BIRD,
                    'RODENT' => AnimalEnum::RODENT,
                ],
            ],
            [
                MixedEnum::class,
                [
                    'COLLECTION' => MixedEnum::COLLECTION,
                    'IS_FALSE'   => MixedEnum::IS_FALSE,
                    'IS_TRUE'    => MixedEnum::IS_TRUE,
                    'IS_NULL'    => MixedEnum::IS_NULL,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getNamesDataProvider
     * @param string $class
     * @param array  $expected
     */
    public function testGetNames(string $class, array $expected)
    {
        $this->assertSame(
            $expected,
            $this->fixture->getNames($class)
        );
    }

    public function getNamesDataProvider()
    {
        return [
            [
                EmptyEnum::class,
                [],
            ],
            [
                AnimalEnum::class,
                [
                    'CAT',
                    'DOG',
                    'BIRD',
                    'RODENT',
                ],
            ],
            [
                MixedEnum::class,
                [
                    'COLLECTION',
                    'IS_FALSE',
                    'IS_TRUE',
                    'IS_NULL',
                ],
            ],
        ];
    }
}
