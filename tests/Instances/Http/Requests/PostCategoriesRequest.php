<?php

	namespace Hans\Valravn\Tests\Instances\Http\Requests;

	use Hans\Valravn\Tests\Core\Models\Post;
	use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;

	class PostCategoriesRequest extends BelongsToManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
		protected function model(): string {
			return Post::class;
		}

	}