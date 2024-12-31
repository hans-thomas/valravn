<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Illuminate\Database\Eloquent\Model;

class SampleWithTypeOverrideResource extends VJsonResource
{
    public function extract(Model $model): ?array
    {
        return [
            'id'   => $model->id,
            'name' => $model->name,
            'type' => 'leaving heaven',
        ];
    }

    public function type(): string
    {
        return 'samples';
    }
}
