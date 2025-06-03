<?php

namespace Hans\Valravn\Tests\Core\Resources\Comment;

use Hans\Valravn\Http\Resources\VResourceCollection;
use Hans\Valravn\Tests\Instances\Http\Includes\PostVIncludes;
use Illuminate\Database\Eloquent\Model;

class CommentCollection extends VResourceCollection
{
    /**
     * @return array
     */
    public function getAvailableVIncludes(): array
    {
        return [
            'post' => PostVIncludes::class,
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
