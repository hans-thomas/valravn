<?php

namespace Hans\Valravn\Tests\Core\Resources\Category;

use Hans\Valravn\Http\Resources\VResourceCollection;
use Hans\Valravn\Tests\Instances\Http\Includes\PostsIncludes;
use Illuminate\Database\Eloquent\Model;

class CategoryCollection extends VResourceCollection
{
    /**
     * @return array
     */
    public function getAvailableIncludes(): array
    {
        return [
            'posts' => PostsIncludes::class,
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
        return 'categories';
    }
}
