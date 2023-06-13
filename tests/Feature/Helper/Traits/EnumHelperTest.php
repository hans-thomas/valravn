<?php

	namespace Hans\Valravn\Tests\Feature\Helper\Traits;

	use Hans\Valravn\Tests\Instances\Helper\SampleEnum;
	use Hans\Valravn\Tests\TestCase;

	class EnumHelperTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function toArray(): void {
			self::assertEquals(
				[
					'FIRST'  => 'FIRST',
					'SECOND' => 'SECOND',
					'THIRD'  => 'THIRD',
				],
				SampleEnum::toArray()
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function toArrayKeys(): void {
			self::assertEquals(
				[
					'FIRST',
					'SECOND',
					'THIRD',
				],
				SampleEnum::toArrayKeys()
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function toArrayExcept(): void {
			self::assertEquals(
				[
					'FIRST'  => 'FIRST',
					'SECOND' => 'SECOND',
				],
				SampleEnum::toArrayExcept( [ 'THIRD' ] )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function toArrayKeysExcept(): void {
			self::assertEquals(
				[
					'FIRST',
					'SECOND',
				],
				SampleEnum::toArrayKeysExcept( [ 'THIRD' ] )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function toArrayOnly(): void {
			self::assertEquals(
				[
					'THIRD' => 'THIRD',
				],
				SampleEnum::toArrayOnly( [ 'THIRD' ] )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function toArrayKeysOnly(): void {
			self::assertEquals(
				[
					'THIRD',
				],
				SampleEnum::toArrayKeysOnly( [ 'THIRD' ] )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function all(): void {
			self::assertEquals(
				[
					'FIRST'  => SampleEnum::FIRST,
					'SECOND' => SampleEnum::SECOND,
					'THIRD'  => SampleEnum::THIRD,
				],
				SampleEnum::all()
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function IndexedAll(): void {
			self::assertEquals(
				[
					SampleEnum::FIRST,
					SampleEnum::SECOND,
					SampleEnum::THIRD,
				],
				SampleEnum::IndexedAll()
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function tryFromKeyAsNotExistsKey(): void {
			self::assertEquals(
				'Not found!',
				SampleEnum::tryFromKey( 'FOURTH', 'Not found!' )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function tryFromKey(): void {
			self::assertEquals(
				SampleEnum::THIRD,
				SampleEnum::tryFromKey( 'THIRD', 'Not found!' )
			);
		}

	}
