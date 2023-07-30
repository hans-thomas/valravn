<?php

namespace Hans\Valravn\Tests\Feature\Helper\Traits;

    use Hans\Valravn\Tests\Instances\Helper\SampleWithStringValueEnum;
    use Hans\Valravn\Tests\TestCase;

    class EnumHelperWithStringValueTest extends TestCase
    {
        /**
         * @test
         *
         * @return void
         */
        public function toArray(): void
        {
            self::assertEquals(
                [
                    'FIRST'  => 'first value',
                    'SECOND' => 'second value',
                    'THIRD'  => 'third value',
                ],
                SampleWithStringValueEnum::toArray()
            );
        }

        /**
         * @test
         *
         * @return void
         */
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

        /**
         * @test
         *
         * @return void
         */
        public function toArrayExcept(): void
        {
            self::assertEquals(
                [
                    'FIRST'  => 'first value',
                    'SECOND' => 'second value',
                ],
                SampleWithStringValueEnum::toArrayExcept(['third value'])
            );
        }

        /**
         * @test
         *
         * @return void
         */
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

        /**
         * @test
         *
         * @return void
         */
        public function toArrayOnly(): void
        {
            self::assertEquals(
                [
                    'THIRD' => 'third value',
                ],
                SampleWithStringValueEnum::toArrayOnly(['third value'])
            );
        }

        /**
         * @test
         *
         * @return void
         */
        public function toArrayKeysOnly(): void
        {
            self::assertEquals(
                [
                    'THIRD',
                ],
                SampleWithStringValueEnum::toArrayKeysOnly(['THIRD'])
            );
        }

        /**
         * @test
         *
         * @return void
         */
        public function all(): void
        {
            self::assertEquals(
                [
                    'FIRST'  => SampleWithStringValueEnum::FIRST,
                    'SECOND' => SampleWithStringValueEnum::SECOND,
                    'THIRD'  => SampleWithStringValueEnum::THIRD,
                ],
                SampleWithStringValueEnum::all()
            );
        }

        /**
         * @test
         *
         * @return void
         */
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

        /**
         * @test
         *
         * @return void
         */
        public function tryFromKeyAsNotExistsKey(): void
        {
            self::assertEquals(
                'Not found!',
                SampleWithStringValueEnum::tryFromKey('FOURTH', 'Not found!')
            );
        }

        /**
         * @test
         *
         * @return void
         */
        public function tryFromKey(): void
        {
            self::assertEquals(
                SampleWithStringValueEnum::THIRD,
                SampleWithStringValueEnum::tryFromKey('THIRD', 'Not found!')
            );
        }
    }
