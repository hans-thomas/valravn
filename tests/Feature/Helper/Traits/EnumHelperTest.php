<?php

namespace Hans\Valravn\Tests\Feature\Helper\Traits;

use Hans\Valravn\Tests\Instances\Helper\SampleEnum;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EnumHelperTest extends TestCase
{
    #[Test]
    public function toArray(): void
    {
        self::assertEquals(
            [
                'FIRST'  => 'FIRST',
                'SECOND' => 'SECOND',
                'THIRD'  => 'THIRD',
            ],
            SampleEnum::toArray()
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
            SampleEnum::toArrayKeys()
        );
    }

    #[Test]
    public function toArrayExcept(): void
    {
        self::assertEquals(
            [
                'FIRST'  => 'FIRST',
                'SECOND' => 'SECOND',
            ],
            SampleEnum::toArrayExcept(['THIRD'])
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
            SampleEnum::toArrayKeysExcept(['THIRD'])
        );
    }

    #[Test]
    public function toArrayOnly(): void
    {
        self::assertEquals(
            [
                'THIRD' => 'THIRD',
            ],
            SampleEnum::toArrayOnly(['THIRD'])
        );
    }

    #[Test]
    public function toArrayKeysOnly(): void
    {
        self::assertEquals(
            [
                'THIRD',
            ],
            SampleEnum::toArrayKeysOnly(['THIRD'])
        );
    }

    #[Test]
    public function all(): void
    {
        self::assertEquals(
            [
                'FIRST'  => SampleEnum::FIRST,
                'SECOND' => SampleEnum::SECOND,
                'THIRD'  => SampleEnum::THIRD,
            ],
            SampleEnum::all()
        );
    }

    #[Test]
    public function IndexedAll(): void
    {
        self::assertEquals(
            [
                SampleEnum::FIRST,
                SampleEnum::SECOND,
                SampleEnum::THIRD,
            ],
            SampleEnum::IndexedAll()
        );
    }

    #[Test]
    public function tryFromKeyAsNotExistsKey(): void
    {
        self::assertEquals(
            'Not found!',
            SampleEnum::tryFromKey('FOURTH', 'Not found!')
        );
    }

    #[Test]
    public function tryFromKey(): void
    {
        self::assertEquals(
            SampleEnum::THIRD,
            SampleEnum::tryFromKey('THIRD', 'Not found!')
        );
    }
}
