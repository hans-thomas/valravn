<?php

namespace Hans\Valravn\Tests\Instances\Http\Queries;

use Hans\Valravn\Http\Resources\Contracts\VResourceQuery;
use Hans\Valravn\Tests\Core\Resources\Comment\CommentResource;
use Illuminate\Database\Eloquent\Model;

class FirstCommentQueryV extends VResourceQuery
{
    /**
     * @param Model $model
     *
     * @return array
     */
    public function apply(Model $model): array
    {
        return [
            'first_comment' => CommentResource::make($model->comments()->limit(1)->first()),
        ];
    }
}
