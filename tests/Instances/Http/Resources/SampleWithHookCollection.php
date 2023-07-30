<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SampleWithHookCollection extends ValravnResourceCollection
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
     * @param ValravnJsonResource|null $resource
     *
     * @return void
     */
    protected function loaded(&$data, ValravnJsonResource $resource = null): void
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
