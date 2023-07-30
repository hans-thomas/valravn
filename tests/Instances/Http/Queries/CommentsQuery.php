<?php

namespace Hans\Valravn\Tests\Instances\Http\Queries;

use Hans\Valravn\Http\Resources\Contracts\CollectionQuery;
use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Hans\Valravn\Tests\Core\Models\Comment;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Core\Resources\Comment\CommentCollection;
use Illuminate\Support\Collection;

class CommentsQuery extends CollectionQuery
{
    /**
     * @param ValravnJsonResource $resource
     *
     * @return array
     */
    public function apply(ValravnJsonResource $resource): array
    {
        $ids = $resource->resource instanceof Collection ?
            $resource->resource->map(fn ($value) => ['id' => $value->id])->flatten() :
            [$resource->resource->id];

        return [
            'all_comments' => CommentCollection::make(
                Comment::query()
                       ->whereIn(( new Post() )->getForeignKey(), $ids)
                       ->get()
            ),
        ];
    }
}
