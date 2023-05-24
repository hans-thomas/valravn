<?php

	namespace Hans\Tests\Valravn\Instances\Http\Requests;

	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToManyRequest;

	class PostCategoriesMorphToManyRequest extends MorphToManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
		protected function model(): string {
			return Post::class;
		}

	}