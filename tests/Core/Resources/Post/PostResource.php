<?php

namespace Hans\Valravn\Tests\Core\Resources\Post;

use Hans\Valravn\Http\Resources\VJsonResource;
use Hans\Valravn\Tests\Instances\Http\Includes\CategoriesVIncludes;
use Hans\Valravn\Tests\Instances\Http\Includes\CommentsVIncludes;
use Hans\Valravn\Tests\Instances\Http\Queries\FirstCategoryQueryV;
use Hans\Valravn\Tests\Instances\Http\Queries\FirstCommentQueryV;
use Illuminate\Database\Eloquent\Model;

class PostResource extends VJsonResource
{
    /**
     * List of available queries of this resource.
     *
     * @return array
     */
    public function getAvailableQueries(): array
    {
        return [
            'with_first_comment'  => FirstCommentQueryV::class,
            'with_first_category' => FirstCategoryQueryV::class,
        ];
    }

    /**
     * List of available includes of this resource.
     *
     * @return array
     */
    public function getAvailableIncludes(): array
    {
        return [
            'comments'   => CommentsVIncludes::class,
            'categories' => CategoriesVIncludes::class,
        ];
    }

    /**
     * @param Model $model
     *
     * @return array|null
     */
    public function extract(Model $model): ?array
    {
        return [
            'id'      => $model->id,
            'title'   => $model->title,
            'content' => $model->content,
        ];
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'posts';
    }

    public function withFirstCommentQuery(): self
    {
        $this->registerQuery(FirstCommentQueryV::class);

        return $this;
    }

    public function withCommentsIncludes(): self
    {
        $this->registerInclude(CommentsVIncludes::class);

        return $this;
    }
}
