<?php

namespace Hans\Valravn\Services\Includes;

use Hans\Valravn\Http\Resources\Contracts\VIncludes;
use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IncludingService
{
    private VJsonResource $resource;
    private array $data = [];
    private array $registeredActions;

    public function __construct(VJsonResource $resource)
    {
        $this->resource = $resource;
        $this->registeredActions = valravn_config('actions');
    }

    /**
     * Register includes using a query string.
     *
     * @param string|array|null $includes
     *
     * @return $this
     */
    public function registerIncludesUsingQueryString(string|array|null $includes): self
    {
        if (is_null($includes)) {
            return $this;
        }

        if (is_string($includes)) {
            $includes = explode(',', $includes);
        }

        foreach ($includes as $include) {
            $data = $this->parseInclude($include);
            if (is_null($data['relation'])) {
                continue;
            }

            if (!empty($data['nested'])) {
                $this->resource->setNestedEagerLoadsFor($data['relation'], $data['nested']);
            }
            $this->resource
                ->registerInclude(
                    $this->getAvailableIncludes()[$data['relation']],
                    $data['actions']
                );
        }

        return $this;
    }

    /**
     * If true, then register the includes.
     *
     * @param bool              $condition
     * @param string|array|null $includes
     *
     * @return $this
     */
    public function registerIncludesUsingQueryStringWhen(bool $condition, string|array|null $includes): self
    {
        if ($condition) {
            $this->registerIncludesUsingQueryString($includes);
        }

        return $this;
    }

    /**
     * Parse the given include and make it ready to apply.
     *
     * @param string $include
     *
     * @return array
     */
    public function parseInclude(string $include): array
    {
        $data['relation'] = null;
        $data['actions'] = [];
        $data['nested'] = [];

        $relation = Str::of($include)->before(':')->before('.')->toString();
        if (!key_exists($relation, $this->getAvailableIncludes())) {
            return $data;
        }
        $data['relation'] = $relation;

        // nested data
        $nested = Str::of($include)
                               ->substr(Str::of($include)->before('.')->length())
                               ->after('.')
                               ->toString();
        $data['nested'] = $nested;

        // actions data
        $filters = Str::of($include)
                      ->replace("$relation.", '')
                      ->replace($data['nested'], '')
                      ->before('.')
                      ->explode(':');
        foreach ($filters as $filter) {
            $action = Str::before($filter, '(');
            $params = Str::of($filter)
                         ->substr(strlen($action))
                         ->before('.')
                         ->trim('()')
                         ->explode('|')
                         ->toArray();

            if (!key_exists($action, $this->registeredActions)) {
                continue;
            }
            $action = $this->registeredActions[$action];
            $data['actions'][$action] = $params;
        }

        return $data;
    }

    /**
     * Apply the Includes on the related resource instance.
     *
     * @param Model $model
     *
     * @return $this
     */
    public function applyRequestedIncludes(Model $model): self
    {
        foreach ($this->resource->getRequestedIncludes() as $include => $actions) {
            $this->data[$this->getInstanceKey($include)] = app($include)->run($model)
                                                                          ->registerActions($actions)
                                                                          ->applyActions()
                                                                          ->toVResource();
            if (key_exists($this->getInstanceKey($include), $this->resource->getNestedEagerLoads())) {
                $this->data[$this->getInstanceKey($include)]
                    ->applyNestedEagerLoadsOnRelation(
                        $this->resource->getNestedEagerLoadsFor($this->getInstanceKey($include))
                    );
            }
        }

        return $this;
    }

    /**
     * Get the available includes list from resource instance.
     *
     * @return array
     */
    protected function getAvailableIncludes(): array
    {
        return $this->resource->getAvailableVIncludes();
    }

    /**
     * Return the instance key using an include instance.
     *
     * @param string|object $instance
     *
     * @return string
     */
    protected function getInstanceKey(string|object $instance): string
    {
        $instance = is_object($instance) ? get_class($instance) : $instance;

        return array_flip($this->getAvailableIncludes())[$instance];
    }

    /**
     * Return Included data.
     *
     * @return array
     */
    public function getIncludedData(): array
    {
        return $this->data;
    }

    /**
     * Will merge included data to the given array.
     *
     * @param array $data
     *
     * @return void
     */
    public function mergeIncludedDataTo(array &$data): void
    {
        $data = array_merge($data, $this->getIncludedData());
    }

    /**
     * Return the list of registered actions.
     *
     * @return array
     */
    public function getRegisteredActions(): array
    {
        return $this->registeredActions;
    }
}
