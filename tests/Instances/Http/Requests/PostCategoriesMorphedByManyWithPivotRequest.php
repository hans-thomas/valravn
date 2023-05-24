<?php

	namespace Hans\Tests\Valravn\Instances\Http\Requests;

	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Valravn\Http\Requests\Contracts\Relations\MorphedByManyRequest;

	class PostCategoriesMorphedByManyWithPivotRequest extends MorphedByManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
		protected function model(): string {
			return Post::class;
		}

		/**
		 * Get pivot columns and their validation rules
		 *
		 * @return array
		 */
		protected function pivots(): array {
			return [
				'order' => [ 'numeric', 'min:1', 'max:99' ],
				'info'  => [ 'string', 'max:128' ],
			];
		}


	}