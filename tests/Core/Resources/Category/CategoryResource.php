<?php

namespace Hans\Valravn\Tests\Core\Resources\Category;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Illuminate\Database\Eloquent\Model;

class CategoryResource extends ValravnJsonResource
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
