<?php

	namespace Hans\Tests\Valravn\Core\Resources\Post;

	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class PostCollection extends BaseResourceCollection {

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
			return 'posts';
		}
	}