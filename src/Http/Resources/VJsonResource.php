<?php

namespace Hans\Valravn\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource as VJsonResourceContract;
use Hans\Valravn\Http\Resources\Traits\VJsonResourceExtender;
use Hans\Valravn\Services\Includes\IncludingService;
use Hans\Valravn\Services\Queries\QueryingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class VJsonResource extends JsonResource implements VJsonResourceContract
{
    use VJsonResourceExtender;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        if (is_null($this->resource)) {
            return [];
        }

        $extracted = $this->extract($this->resource);
        if (count($extracted ?? []) <= 0) {
            $extracted = $this->resource->toArray();
        }
        $data = array_merge(['type' => $this->type()], $extracted);

        if ($this->resource instanceof Model) {
            app(IncludingService::class, ['resource' => $this])
                ->registerIncludesUsingQueryStringWhen($this->shouldParseIncludes(), $request->get('includes'))
                ->applyRequestedIncludes($this->resource)
                ->mergeIncludedDataTo($data);

            $this->loadedRelations($data);
            $this->loadedPivots($data);

            app(QueryingService::class, ['resource' => $this])
                ->registerQueriesUsingQueryStringWhen($this->shouldParseQueries(), $request->getQueryString())
                ->applyRequestedQueries($this->resource)
                ->mergeQueriedDataInto($data);
        }

        if (!empty($this->getExtra())) {
            $data = array_merge($data, ['extra' => $this->getExtra()]);
        }

        $this->loaded($data);

        $this->applyOnly($data);

        return $data;
    }
}
