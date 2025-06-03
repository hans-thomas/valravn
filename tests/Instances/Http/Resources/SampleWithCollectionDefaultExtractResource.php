<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\VJsonResource;
use Illuminate\Database\Eloquent\Model;

class SampleWithCollectionDefaultExtractResource extends VJsonResource
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
            'extract' => 'not default on resource',
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
