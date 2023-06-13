<?php

	namespace Hans\Valravn\Tests\Feature\Http\Requests;

	use Hans\Valravn\Tests\Core\Models\Post;
	use Hans\Valravn\Tests\Instances\Http\Requests\PostBatchUpdateRequest;
	use Hans\Valravn\Tests\TestCase;
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