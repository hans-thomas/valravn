<?php

	namespace Hans\Tests\Valravn\Core\Resources\Comment;

	use Hans\Tests\Valravn\Instances\Http\Includes\PostIncludes;
	use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
	use Illuminate\Database\Eloquent\Model;

	class CommentResource extends ValravnJsonResource {

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
			return [
				'id'      => $model->id,
				'content' => $model->content,
			];
		}

		/**
		 * @return string
		 */
		public function type(): string {
			return 'comments';
		}
	}