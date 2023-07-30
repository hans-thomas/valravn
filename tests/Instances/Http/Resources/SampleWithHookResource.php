<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Illuminate\Database\Eloquent\Model;

class SampleWithHookResource extends ValravnJsonResource
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

    /**
     * Executes when data loaded.
     *
     * @param $data
     *
     * @return void
     */
    protected function loaded(&$data): void
    {
        $data['sober'] = 'i might regret this when tomorrow comes';
    }
}
