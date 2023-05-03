<?php

	namespace Hans\Tests\Valravn\Feature\DTOs;

	use Hans\Tests\Valravn\Instances\DTOs\SampleDto;
	use Hans\Tests\Valravn\TestCase;

	class DtoTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function make(): void {
			$data   = [
				[ 'id' => 1 ],
				[ 'id' => 3 ],
			];
			$result = SampleDto::make( [
				'related' => $data
			] );
			self::assertEquals(
				[
					[ 'id' => 1 ],
					[ 'id' => 3 ],
				],
				$result->getData()->toArray()
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function makeFromArray(): void {
			$data   = [
				1,
				3 => [
					'the art'    => "i ain't even see the face, but she got beautiful boobies.",
					'the artist' => 'post malone'
				]
			];
			$result = SampleDto::makeFromArray( $data );
			self::assertEquals(
				[
					[ 'id' => 1 ],
					[
						"id"    => 3,
						"pivot" => [
							"the art"    => "i ain't even see the face, but she got beautiful boobies.",
							"the artist" => "post malone"
						]
					]
				],
				$result->getData()->toArray()
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function export(): void {
			$data   = [
				[ 'id' => 1 ],
				[
					'id'    => 3,
					'pivot' => [
						'the art'    => "i ain't even see the face, but she got beautiful boobies.",
						'the artist' => 'post malone'
					]
				],
			];
			$result = SampleDto::export( [ 'related' => $data ] );
			self::assertEquals(
				[
					[ 'id' => 1 ],
					[
						'id'    => 3,
						'pivot' => [
							'the art'    => "i ain't even see the face, but she got beautiful boobies.",
							'the artist' => 'post malone'
						]
					],
				],
				$result->toArray()
			);
		}


	}