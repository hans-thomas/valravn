<?php

	namespace Hans\Tests\Valravn\Core\Resources\Comment;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Database\Eloquent\Model;

	class CommentResource extends BaseJsonResource {

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