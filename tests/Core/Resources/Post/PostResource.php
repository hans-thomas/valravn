<?php

	namespace Hans\Tests\Valravn\Core\Resources\Post;

	use Hans\Tests\Valravn\Instances\Http\Includes\CommentsIncludes;
	use Hans\Tests\Valravn\Instances\Http\Queries\FirstCommentQuery;
	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Database\Eloquent\Model;

	class PostResource extends BaseJsonResource {

		/**
		 * List of available queries of this resource
		 *
		 * @return array
		 */
		public function getAvailableQueries(): array {
			return [
				'with_first_comment' => FirstCommentQuery::class,
			];
		}

		/**
		 * List of available includes of this resource
		 *
		 * @return array
		 */
		public function getAvailableIncludes(): array {
			return [
				'comments' => CommentsIncludes::class,
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
				'title'   => $model->title,
				'content' => $model->content,
			];
		}

		/**
		 * @return string
		 */
		public function type(): string {
			return 'posts';
		}

		public function withFirstCommentQuery(): self {
			$this->registerQuery( FirstCommentQuery::class );

			return $this;
		}

		public function withCommentsIncludes(): self {
			$this->registerInclude( CommentsIncludes::class );

			return $this;
		}

	}