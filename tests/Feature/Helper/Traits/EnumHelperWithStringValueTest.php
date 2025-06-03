<?php

namespace Hans\Valravn\Tests\Feature\Helper\Traits;

use Hans\Valravn\Tests\Instances\Helper\SampleWithStringValueEnum;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EnumHelperWithStringValueTest extends TestCase
{
    #[Test]
    public function toArray(): void
    {
        self::assertEquals(
            [
                'FIRST' => 'first value',
                'SECOND' => 'second value',
                'THIRD' => 'third value',
            ],
            SampleWithStringValueEnum::toArray()
        );
    }

    #[Test]
    public function toArrayKeys(): void
    {
        self::assertEquals(
            [
                'FIRST',
                'SECOND',
                'THIRD',
            ],
            SampleWithStringValueEnum::toArrayKeys()
        );
    }

    #[Test]
    public function toArrayExcept(): void
    {
        self::assertEquals(
            [
                'FIRST' => 'first value',
                'SECOND' => 'second value',
            ],
            SampleWithStringValueEnum::toArrayExcept(['third value'])
        );
    }

    #[Test]
    public function toArrayKeysExcept(): void
    {
        self::assertEquals(
            [
                'FIRST',
                'SECOND',
            ],
            SampleWithStringValueEnum::toArrayKeysExcept(['THIRD'])
        );
    }

    #[Test]
    public function toArrayOnly(): void
    {
        self::assertEquals(
            [
                'THIRD' => 'third value',
            ],
            SampleWithStringValueEnum::toArrayOnly(['third value'])
        );
    }

    #[Test]
    public function toArrayKeysOnly(): void
    {
        self::assertEquals(
            [
                'THIRD',
            ],
            SampleWithStringValueEnum::toArrayKeysOnly(['THIRD'])
        );
    }

    #[Test]
    public function all(): void
    {
        self::assertEquals(
            [
                'FIRST' => SampleWithStringValueEnum::FIRST,
                'SECOND' => SampleWithStringValueEnum::SECOND,
                'THIRD' => SampleWithStringValueEnum::THIRD,
            ],
            SampleWithStringValueEnum::all()
        );
    }

    #[Test]
    public function IndexedAll(): void
    {
        self::assertEquals(
            [
                SampleWithStringValueEnum::FIRST,
                SampleWithStringValueEnum::SECOND,
                SampleWithStringValueEnum::THIRD,
            ],
            SampleWithStringValueEnum::IndexedAll()
        );
    }

    #[Test]
    public function tryFromKeyAsNotExistsKey(): void
    {
        self::assertEquals(
            'Not found!',
            SampleWithStringValueEnum::tryFromKey('FOURTH', 'Not found!')
        );
    }

    #[Test]
    public function tryFromKey(): void
    {
        self::assertEquals(
            SampleWithStringValueEnum::THIRD,
            SampleWithStringValueEnum::tryFromKey('THIRD', 'Not found!')
        );
    }
}
