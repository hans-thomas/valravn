<?php

	namespace Hans\Tests\Valravn\Feature\Http\Requests;

	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Instances\Http\Requests\PostBatchUpdateRequest;
	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Validation\Rule;

	class BatchUpdateRequestTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function batchUpdate(): void {
			$rules = app( PostBatchUpdateRequest::class )->rules();

			self::assertEquals(
				[
					'batch'           => [ 'array' ],
					'batch.*.id'      => [ 'required', 'numeric', Rule::exists( Post::class, 'id' ) ],
					'batch.*.title'   => [ 'string', 'max:255' ],
					'batch.*.content' => [ 'string' ],
				],
				$rules
			);
		}

	}