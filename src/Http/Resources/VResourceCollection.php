<?php

namespace Hans\Valravn\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource as VJsonResourceContract;
use Hans\Valravn\Http\Resources\Contracts\VResourceCollection as VResourceCollectionContract;
use Hans\Valravn\Http\Resources\Traits\VJsonResourceExtender;
use Hans\Valravn\Services\Includes\IncludingService;
use Hans\Valravn\Services\Queries\QueryingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

abstract class VResourceCollection extends ResourceCollection implements VJsonResourceContract, VResourceCollectionContract
{
    use VJsonResourceExtender;

    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     *
     * @return void
     */
    public function __construct($resource)
    {
        $resource ??= [];
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        app(QueryingService::class, ['resource' => $this])
            ->registerQueriesUsingQueryStringWhen($this->shouldParseQueries(), $request->getQueryString());

        // TODO: error possibility: $item might be a Model instance if there was not any resource class
        $response = $this->collection->map(function (VJsonResource $item) use ($request) {
            $extracted = $this->extract($item->resource) ?:
                $item->extract($item->resource) ?:
                    $item->resource->toArray();
            $data = array_merge(['type' => $this->type()], $extracted);

            if ($item->resource instanceof Model) {
                app(IncludingService::class, ['resource' => $this])
                    ->registerIncludesUsingQueryStringWhen(
                        $this->shouldParseIncludes(),
                        $request->get('includes')
                    )
                    ->applyRequestedIncludes($item->resource)
                    ->mergeIncludedDataTo($data);

                $this->loadedRelations($data, $item);
                $this->loadedPivots($data, $item);

                app(QueryingService::class, ['resource' => $this])
                    ->applyRequestedQueries($item->resource)
                    ->mergeQueriedDataInto($data);
            }

            $this->loaded($data, $item);

            if (!empty($item->getExtra())) {
                $data = array_merge($data, ['extra' => $item->getExtra()]);
            }

            $this->applyOnly($data);

            return $data;
        });

        app(QueryingService::class, ['resource' => $this])
            ->applyRequestedCollectionQueries()
            ->mergeCollectionQueriedData();

        $this->allLoaded($response);

        return $response->toArray();
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
        // ...
    }

    /**
     * Executes when data loaded.
     *
     * @param                    $data
     * @param VJsonResource|null $resource
     *
     * @return void
     */
    protected function loaded(&$data, ?VJsonResource $resource = null): void
    {
        // ...
    }
}
