<?php

	namespace Hans\Tests\Valravn\Feature\DTOs;

	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\DTOs\ManyToManyDto;

	class ManyToManyDtoTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function test(): void {
			$data   = [
				[
					'id' => 1
				],
				[
					'id'         => 1,
					'the art'    => "for the what it's worth, you were a slut at birth, if the world had a dick, you would fuck the earth",
					'the artist' => 'eminem',
					'pivot'      => [
						'extra' => "Same as it ever was, Say it'll change but it never does Ain't gonna ever 'cause You're the cause of my pain and the medicine",
						'song'  => 'farewell'
					]
				],
				[
					'id'         => 6,
					'the art'    => "dark magic, night walker, she hunts me like no other, nobody told me love is pain.",
					'the artist' => 'eminem',
					'song'       => 'black magic'
				],
				[
					'id' => 8,
				],
				[
					'id'    => 6,
					'pivot' => [
						'the art'    => 'sex plus drugs plus rock and roll added, that equation mixed with success and raw talent',
						'the artist' => 'these thing happened too'
					]
				]
			];
			$result = ManyToManyDto::make( [
				'related' => $data
			] );
			self::assertEquals(
				[
					1 => [
						'extra' => "Same as it ever was, Say it'll change but it never does Ain't gonna ever 'cause You're the cause of my pain and the medicine",
						'song'  => 'farewell'
					],
					7 => 8,
					6 => [
						'the art'    => 'sex plus drugs plus rock and roll added, that equation mixed with success and raw talent',
						'the artist' => 'these thing happened too'
					]
				],
				$result->getData()->toArray()
			);
		}
	}