<?php

	namespace Hans\Tests\Valravn\Core\Resources\Category;

	use Hans\Tests\Valravn\Instances\Http\Includes\PostsIncludes;
	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class CategoryCollection extends BaseResourceCollection {
		/**
		 * @return array
		 */
		public function getAvailableIncludes(): array {
			return [
				'posts' => PostsIncludes::class,
			];
		}


		/**
		 * @param Model $model
		 *
		 * @return array|null
		 */
		public function extract( Model $model ): ?array {
			return null;
		}

		/**
		 * @return string
		 */
		public function type(): string {
			return 'categories';
		}
	}