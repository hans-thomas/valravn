<?php

namespace Hans\Valravn\Tests\Instances\Http\Includes;

use Hans\Valravn\Http\Resources\Contracts\VIncludes;
use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Hans\Valravn\Tests\Core\Resources\Category\CategoryCollection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CategoriesVIncludes extends VIncludes
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
     * @return VJsonResource
     */
    public function toVResource(): VJsonResource
    {
        return CategoryCollection::make($this->getBuilder()->get());
    }
}
