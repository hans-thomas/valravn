<?php

	namespace Hans\Tests\Valravn\Core\Resources\Post;

	use Hans\Tests\Valravn\Instances\Http\Queries\CommentsQuery;
	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class PostCollection extends BaseResourceCollection {

		/**
		 * @return array
		 */
		public function getAvailableQueries(): array {
			return [
				'all-comments' => CommentsQuery::class,
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
	}