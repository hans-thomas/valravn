<?php

	namespace Hans\Tests\Valravn\Feature\DTOs;

	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\DTOs\BatchUpdateDto;

	class BatchUpdateDtoTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function test(): void {
			$data   = [
				[ 'id' => 1 ],
				[ 'id' => 1, 'the art' => "no limit i'm a fucking soldier" ],
				[ 'id' => 5, 'the artist' => 'g-eazy' ],
			];
			$result = BatchUpdateDto::make( [
				'batch' => $data
			] );

			self::assertEquals(
				[
					[ 'id' => 1, 'the art' => "no limit i'm a fucking soldier" ],
					[ 'id' => 5, 'the artist' => 'g-eazy' ],
				],
				array_values( $result->getData()->toArray() )
			);
		}

	}