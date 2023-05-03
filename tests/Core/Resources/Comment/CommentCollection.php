<?php

	namespace Hans\Tests\Valravn\Core\Resources\Comment;

	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class CommentCollection extends BaseResourceCollection {

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
			return 'comments';
		}
	}