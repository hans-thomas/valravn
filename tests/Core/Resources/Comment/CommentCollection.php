<?php

	namespace Hans\Tests\Valravn\Core\Resources\Comment;

	use Hans\Tests\Valravn\Instances\Http\Includes\PostIncludes;
	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class CommentCollection extends BaseResourceCollection {

		/**
		 * @return array
		 */
		public function getAvailableIncludes(): array {
			return [
				'post' => PostIncludes::class,
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
			return 'comments';
		}
	}