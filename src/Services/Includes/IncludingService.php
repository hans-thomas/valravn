<?php

namespace Hans\Valravn\Services\Includes;

    use Hans\Valravn\Http\Resources\Contracts\Includes;
    use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;

    class IncludingService
    {
        private ValravnJsonResource $resource;
        private array $data = [];
        private array $registeredActions;

        /**
         * @param ValravnJsonResource $resource
         */
        public function __construct(ValravnJsonResource $resource)
        {
            $this->resource = $resource;
            $this->registeredActions = valravn_config('actions');
        }

        /**
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

        public function registerIncludesUsingQueryStringWhen(bool $condition, string|array|null $includes): self
        {
            if ($condition) {
                $this->registerIncludesUsingQueryString($includes);
            }

            return $this;
        }

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

        public function applyRequestedIncludes(Model $model): self
        {
            foreach ($this->resource->getRequestedIncludes() as $include => $actions) {
                $this->data[$this->getInstanceKey($include)] = app($include)->run($model)
                                                                                  ->registerActions($actions)
                                                                                  ->applyActions()
                                                                                  ->toResource();
                if (key_exists($this->getInstanceKey($include), $this->resource->getNestedEagerLoads())) {
                    $this->data[$this->getInstanceKey($include)]
                        ->applyNestedEagerLoadsOnRelation(
                            $this->resource->getNestedEagerLoadsFor($this->getInstanceKey($include))
                        );
                }
            }

            return $this;
        }

        protected function getAvailableIncludes(): array
        {
            return $this->resource->getAvailableIncludes();
        }

        protected function getIncludeInstance(string $include): Includes
        {
            return app($this->getAvailableIncludes()[$include]);
        }

        protected function getInstanceKey(string|object $instance): string
        {
            $instance = is_object($instance) ? get_class($instance) : $instance;

            return array_flip($this->getAvailableIncludes())[$instance];
        }

        /**
         * @return array
         */
        public function getIncludedData(): array
        {
            return $this->data;
        }

        public function mergeIncludedDataTo(array &$data): void
        {
            $data = array_merge($data, $this->getIncludedData());
        }

        /**
         * @return array
         */
        public function getRegisteredActions(): array
        {
            return $this->registeredActions;
        }
    }
