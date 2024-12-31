<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Hans\Valravn\Http\Resources\Contracts\VResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SampleWithHookCollection extends VResourceCollection
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
        ];
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'samples';
    }

    /**
     * Executes when data loaded.
     *
     * @param                          $data
     * @param VJsonResource|null       $resource
     *
     * @return void
     */
    protected function loaded(&$data, VJsonResource $resource = null): void
    {
        $this->addExtra([
            'all-loaded' => 'i might regret this when tomorrow comes',
        ]);
    }

    /**
     * Executes when all items loaded.
     *
     * @param Collection $response
     *
     * @return void
     */
    protected function allLoaded(Collection &$response): void
    {
        $this->addAdditional([
            'all-loaded' => 'will you still love me when i no longer young and beautiful?',
        ]);
    }
}
