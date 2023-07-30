<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Illuminate\Database\Eloquent\Model;

class SampleResource extends ValravnJsonResource
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
