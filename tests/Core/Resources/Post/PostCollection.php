<?php

namespace Hans\Valravn\Tests\Core\Resources\Post;

use Hans\Valravn\Http\Resources\VResourceCollection;
use Hans\Valravn\Tests\Instances\Http\Includes\CommentsVIncludes;
use Hans\Valravn\Tests\Instances\Http\Queries\CommentsQuery;
use Hans\Valravn\Tests\Instances\Http\Queries\FirstCategoryQueryV;
use Hans\Valravn\Tests\Instances\Http\Queries\FirstCommentQueryV;
use Illuminate\Database\Eloquent\Model;

class PostCollection extends VResourceCollection
{
    /**
     * @return array
     */
    public function getAvailableQueries(): array
    {
        return [
            'with_all_comments'   => CommentsQuery::class,
            'with_first_comment'  => FirstCommentQueryV::class,
            'with_first_category' => FirstCategoryQueryV::class,
        ];
    }

    /**
     * List of available includes of this resource.
     *
     * @return array
     */
    public function getAvailableVIncludes(): array
    {
        return [
            'comments' => CommentsVIncludes::class,
        ];
    }

    /**
     * @param Model $model
     *
     * @return array|null
     */
    public function extract(Model $model): ?array
    {
        return null;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'posts';
    }

    public function withAllCommentsQuery(): self
    {
        $this->registerQuery(CommentsQuery::class);

        return $this;
    }

    public function withFirstCommentQuery(): self
    {
        $this->registerQuery(FirstCommentQueryV::class);

        return $this;
    }
}
