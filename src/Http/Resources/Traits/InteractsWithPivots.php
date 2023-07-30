<?php

namespace Hans\Valravn\Http\Resources\Traits;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait InteractsWithPivots
{
    /**
     * Load pivots if exists.
     *
     * @param array                    $data
     * @param ValravnJsonResource|null $resource
     * @param array                    $includes
     * @param array                    $excludes
     * @param array                    $alias
     *
     * @return void
     */
    protected function loadedPivots(
        array &$data,
        ValravnJsonResource $resource = null,
        array $includes = [],
        array $excludes = [],
        array $alias = []
    ): void {
        $instance = $resource ?? $this;
        if (isset($instance->resource->pivot)) {
            $data['pivot'] = collect($instance->resource->pivot->getAttributes())
                ->filter(
                    fn ($value, $key) => !in_array($key, $excludes) and
                                          (!Str::contains($key, ['_id', '_type']) or
                                            in_array($key, $includes))
                )
                ->mapWithKeys(
                    fn ($value, $key) => ($index = array_search($key, array_keys($alias))) !== false ?
                        [
                            Arr::get(array_values($alias), $index) => $value,
                        ] :
                        [$key => $value]
                )
                ->toArray();
        }
    }
}
