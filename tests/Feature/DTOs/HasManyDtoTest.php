<?php

	namespace Hans\Tests\Valravn\Feature\DTOs;

	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\DTOs\HasManyDto;

	class HasManyDtoTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function test(): void {
			$data   = [
				[ 'id' => 1 ],
				[
					'id'      => 1,
					'the art' => "my haters feel like i'm better dead, but i'm quite alive and getting bread instead"
				],
				[
					'id'         => 5,
					'the artist' => 'g-eazy',
					'song'       => 'I Mean It'
				],
			];
			$result = HasManyDto::make( [
				'related' => $data
			] );

			self::assertEquals(
				[ 1, 5 ],
				array_values( $result->getData()->toArray() )
			);
		}

	}