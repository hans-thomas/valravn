<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Illuminate\Database\Eloquent\Model;

class SampleWithDefaultExtractResource extends VJsonResource
{
    public function extract(Model $model): ?array
    {
        return null;
    }

    public function type(): string
    {
        return 'samples';
    }
}
