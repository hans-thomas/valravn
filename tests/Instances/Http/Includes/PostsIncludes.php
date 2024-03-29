<?php

namespace Hans\Valravn\Tests\Instances\Http\Includes;

use Hans\Valravn\Http\Resources\Contracts\Includes;
use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Hans\Valravn\Tests\Core\Resources\Post\PostCollection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostsIncludes extends Includes
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
     * @return ValravnJsonResource
     */
    public function toResource(): ValravnJsonResource
    {
        return PostCollection::make($this->getBuilder()->get());
    }
}
