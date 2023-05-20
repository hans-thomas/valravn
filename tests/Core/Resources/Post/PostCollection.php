<?php

	namespace Hans\Tests\Valravn\Core\Resources\Post;

	use Hans\Tests\Valravn\Instances\Http\Includes\CommentsIncludes;
	use Hans\Tests\Valravn\Instances\Http\Queries\CommentsQuery;
	use Hans\Tests\Valravn\Instances\Http\Queries\FirstCategoryQuery;
	use Hans\Tests\Valravn\Instances\Http\Queries\FirstCommentQuery;
	use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class PostCollection extends ValravnResourceCollection {

		/**
		 * @return array
		 */
		public function getAvailableQueries(): array {
			return [
				'with_all_comments'   => CommentsQuery::class,
				'with_first_comment'  => FirstCommentQuery::class,
				'with_first_category' => FirstCategoryQuery::class,
			];
		}

		/**
		 * List of available includes of this resource
		 *
		 * @return array
		 */
		public function getAvailableIncludes(): array {
			return [
				'comments' => CommentsIncludes::class
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
			return 'posts';
		}

		public function withAllCommentsQuery(): self {
			$this->registerQuery( CommentsQuery::class );

			return $this;
		}

		public function withFirstCommentQuery(): self {
			$this->registerQuery( FirstCommentQuery::class );

			return $this;
		}
	}