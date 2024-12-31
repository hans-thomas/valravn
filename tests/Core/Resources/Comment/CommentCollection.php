<?php

namespace Hans\Valravn\Tests\Core\Resources\Comment;

use Hans\Valravn\Http\Resources\Contracts\VResourceCollection;
use Hans\Valravn\Tests\Instances\Http\Includes\PostIncludes;
use Illuminate\Database\Eloquent\Model;

class CommentCollection extends VResourceCollection
{
    /**
     * @return array
     */
    public function getAvailableIncludes(): array
    {
        return [
            'post' => PostIncludes::class,
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
        return 'comments';
    }
}
