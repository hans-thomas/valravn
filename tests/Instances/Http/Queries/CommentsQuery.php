<?php

	namespace Hans\Tests\Valravn\Instances\Http\Queries;

	use Hans\Tests\Valravn\Core\Models\Comment;
	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Core\Resources\Comment\CommentCollection;
	use Hans\Valravn\Http\Resources\Contracts\CollectionQuery;
	use Illuminate\Http\Resources\Json\JsonResource;
	use Illuminate\Support\Collection;

	class CommentsQuery extends CollectionQuery {

		/**
		 * @param JsonResource $json_resource
		 *
		 * @return array
		 */
		public function apply( JsonResource $json_resource ): array {
			$ids = $json_resource->resource instanceof Collection ?
				$json_resource->resource->map( fn( $value ) => [ 'id' => $value->id ] ) :
				[ $json_resource->resource->id ];

			return [
				'all-comments' => CommentCollection::make( Comment::query()
				                                                  ->whereIn( ( new Post )->getForeignKey(), $ids )
				                                                  ->get() )
			];
		}
	}