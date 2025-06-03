<?php

namespace Hans\Valravn\Tests\Instances\Http\Includes;

use Hans\Valravn\Http\Resources\Contracts\VIncludes;
use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Hans\Valravn\Tests\Core\Resources\Post\PostCollection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostsVIncludes extends VIncludes
{
    /**
     * @param Model $model
     *
     * @return Builder
     */
    public function apply(Model $model): Builder
    {
        return $model->posts();
    }

    /**
     * @return VJsonResource
     */
    public function toVResource(): VJsonResource
    {
        return PostCollection::make($this->getBuilder()->get());
    }
}
