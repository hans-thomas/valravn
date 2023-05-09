<?php

	namespace Hans\Tests\Valravn\Instances\Http\Queries;

	use Hans\Tests\Valravn\Core\Models\Comment;
	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Core\Resources\Comment\CommentCollection;
	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\CollectionQuery;
	use Illuminate\Http\Resources\Json\JsonResource;
	use Illuminate\Support\Collection;

	class CommentsQuery extends CollectionQuery {

		/**
		 * @param BaseJsonResource $resource
		 *
		 * @return array
		 */
		public function apply( BaseJsonResource $resource ): array {
			$ids = $resource->resource instanceof Collection ?
				$resource->resource->map( fn( $value ) => [ 'id' => $value->id ] )->flatten() :
				[ $resource->resource->id ];

			return [
				'all_comments' => CommentCollection::make(
					Comment::query()
					       ->whereIn( ( new Post )->getForeignKey(), $ids )
					       ->get()
				)
			];
		}
	}