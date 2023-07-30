<?php

namespace Hans\Valravn\Services\Filtering;

    use Illuminate\Contracts\Database\Eloquent\Builder;
    use Illuminate\Support\Arr;

    class FilteringService
    {
        private array $registeredFilters;

        public function __construct()
        {
            $this->registeredFilters = valravn_config('filters');
        }

        public function apply(Builder $builder, array $options = []): Builder
        {
            foreach ($this->scopeActions($options) as $key => $filter) {
                if (request()->has($key)) {
                    call_user_func([new $filter(), 'apply'], $builder, request()->input($key));
                }
            }

            return $builder;
        }

        private function scopeActions(array $options): array
        {
            $actions = $this->registeredFilters;
            if (isset($options['only'])) {
                $actions = array_intersect($actions, Arr::wrap($options['only']));
            }

            if (isset($options['except'])) {
                $actions = array_diff($actions, Arr::wrap($options['except']));
            }

            return $actions;
        }

        /**
         * @return array
         */
        public function getRegistered(): array
        {
            return $this->registeredFilters;
        }
    }
