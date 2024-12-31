<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Illuminate\Database\Eloquent\Model;

class SampleWithAliasResource extends VJsonResource
{
    public function extract(Model $model): ?array
    {
        return [
            'id'   => $model->id,
            'name' => $model->name,
        ];
    }

    public function type(): string
    {
        return 'samples';
    }

    protected function loaded(&$data): void
    {
        $this->loadedPivots(
            data: $data,
            alias: [
                'order' => 'lineup',
            ]
        );
    }
}
