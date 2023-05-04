<?php

	namespace Hans\Tests\Valravn\Core\Resources\Post;

	use Hans\Tests\Valravn\Instances\Http\Queries\CommentsQuery;
	use Hans\Tests\Valravn\Instances\Http\Queries\FirstCommentQuery;
	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class PostCollection extends BaseResourceCollection {

		/**
		 * @return array
		 */
		public function getAvailableQueries(): array {
			return [
				'with_all_comments'  => CommentsQuery::class,
				'with_first_comment' => FirstCommentQuery::class,
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