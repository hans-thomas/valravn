<?php

	namespace Hans\Valravn\Tests\Instances\Http\Requests;

	use Hans\Valravn\Tests\Core\Models\Post;
	use Hans\Valravn\Http\Requests\Contracts\BatchUpdateRequest;

	class PostBatchUpdateRequest extends BatchUpdateRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
		protected function model(): string {
			return Post::class;
		}

		/**
		 * Get fields and their validation rules
		 *
		 * @return array
		 */
		protected function fields(): array {
			return [
				'title'   => [ 'string', 'max:255' ],
				'content' => [ 'string' ],
			];
		}
	}