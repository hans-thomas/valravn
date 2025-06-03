<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\VResourceCollection;
use Illuminate\Database\Eloquent\Model;

class SampleCollection extends VResourceCollection
{
    /**
     * @param Model $model
     *
     * @return array|null
     */
    public function extract(Model $model): ?array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
        ];
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'samples';
    }
}
