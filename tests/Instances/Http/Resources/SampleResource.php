<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\VJsonResource;
use Illuminate\Database\Eloquent\Model;

class SampleResource extends VJsonResource
{
    public function extract(Model $model): ?array
    {
        return [
            'id'      => $model->id,
            'name'    => $model->name,
            'email'   => $model->email,
            'address' => $model->address,
        ];
    }

    public function type(): string
    {
        return 'samples';
    }
}
