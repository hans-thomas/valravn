<?php

namespace Hans\Valravn\Tests\Core\Resources\Category;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Illuminate\Database\Eloquent\Model;

class CategoryResource extends VJsonResource
{
    /**
     * @param Model $model
     *
     * @return array|null
     */
    public function extract(Model $model): ?array
    {
        return [
            'id'   => $model->id,
            'name' => $model->name,
        ];
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'categories';
    }
}
