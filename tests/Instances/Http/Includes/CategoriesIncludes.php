<?php

namespace Hans\Valravn\Tests\Instances\Http\Includes;

use Hans\Valravn\Http\Resources\Contracts\Includes;
use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Hans\Valravn\Tests\Core\Resources\Category\CategoryCollection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CategoriesIncludes extends Includes
{
    /**
     * @param Model $model
     *
     * @return Builder
     */
    public function apply(Model $model): Builder
    {
        return $model->categories();
    }

    /**
     * @return ValravnJsonResource
     */
    public function toResource(): ValravnJsonResource
    {
        return CategoryCollection::make($this->getBuilder()->get());
    }
}
