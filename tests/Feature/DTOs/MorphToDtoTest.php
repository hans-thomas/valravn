<?php

	namespace Hans\Tests\Valravn\Feature\DTOs;

	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\DTOs\MorphToDto;

	class MorphToDtoTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function test(): void {
			$data = [
				'entity'    => 'posts',
				'namespace' => 'blog'
			];

			$result = MorphToDto::make( [
				'related' => $data
			] );

			self::assertEquals(
				[ 'posts' ],
				$result->getData()->toArray()
			);
		}

	}