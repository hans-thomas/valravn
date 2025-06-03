<?php

namespace Hans\Valravn\Services\Filtering;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class FilteringService
{
    private array $registered_filters;
    private array $requested_filters;

    public function __construct()
    {
        $this->registered_filters = valravn_config('filters');
    }

    /**
     * @param Builder $builder
     * @param array $options
     *
     * @return Builder
     */
    public function apply(Builder $builder, array $options = []): Builder
    {
        foreach ($this->scopeActions($options) as $key => $filter) {
            if (request()->has($key)) {
                call_user_func([new $filter(), 'apply'], $builder, request()->input($key));
            }
        }

        foreach ($this->getRequested() as $filter => $args) {
            call_user_func([new $filter(), 'apply'], $builder, $args);
        }

        return $builder;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function scopeActions(array $options): array
    {
        $actions = $this->registered_filters;
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
    public function getRequested(): array
    {
        return $this->requested_filters ?? [];
    }

    public function withFilters(array $filters): self
    {
        foreach ($filters as $filter => $args) {
            $this->withFilter($filter, $args);
        }

        return $this;
    }

    public function withFilter(string $filter, array $args): self
    {
        if (in_array($filter, $this->registered_filters)) {
            $this->requested_filters[$filter] = $args;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRegistered(): array
    {
        return $this->registered_filters;
    }
}
