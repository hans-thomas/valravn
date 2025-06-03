<?php

namespace Hans\Valravn\Tests\Instances\Http\Queries;

use Hans\Valravn\Http\Resources\Contracts\VCollectionQuery;
use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Hans\Valravn\Tests\Core\Models\Comment;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Core\Resources\Comment\CommentCollection;
use Illuminate\Support\Collection;

class CommentsQueryV extends VCollectionQuery
{
    /**
     * @param VJsonResource $resource
     *
     * @return array
     */
    public function apply(VJsonResource $resource): array
    {
        $ids = $resource->resource instanceof Collection ?
            $resource->resource->map(fn ($value) => ['id' => $value->id])->flatten() :
            [$resource->resource->id];

        return [
            'all_comments' => CommentCollection::make(
                Comment::query()
                    ->whereIn((new Post())->getForeignKey(), $ids)
                    ->get()
            ),
        ];
    }
}
