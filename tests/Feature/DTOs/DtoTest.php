<?php

	namespace Hans\Tests\Valravn\Feature\DTOs;

	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\DTOs\ManyToManyDto;

	class DtoTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function make(): void {
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
			$result = ManyToManyDto::make( [
				'related' => $data
			] );
			self::assertEquals(
				[
					4 => 1,
					3 => [
						'the art'    => "i ain't even see the face, but she got beautiful boobies.",
						'the artist' => 'post malone'
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
		public function makeFromArray(): void {
			$data   = [
				1,
				3 => [
					'the art'    => "i ain't even see the face, but she got beautiful boobies.",
					'the artist' => 'post malone'
				]
			];
			$result = ManyToManyDto::makeFromArray( $data );
			self::assertEquals(
				[
					4 => 1,
					3 => [
						'the art'    => "i ain't even see the face, but she got beautiful boobies.",
						'the artist' => 'post malone'
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
			$result = ManyToManyDto::export( [ 'related' => $data ] );
			self::assertEquals(
				[
					4 => 1,
					3 => [
						'the art'    => "i ain't even see the face, but she got beautiful boobies.",
						'the artist' => 'post malone'
					]
				],
				$result->toArray()
			);
		}


	}