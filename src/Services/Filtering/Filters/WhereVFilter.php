<?php

namespace Hans\Valravn\Services\Filtering\Filters;

use Hans\Valravn\Services\Contracts\Filters\VFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class WhereVFilter extends VFilter
{
    public function apply(Builder $builder, $values = null)
    {
        foreach ($values ?? [] as $attribute => $where) {
            $items = collect(explode(',', $where))
                ->map(static fn ($value) => filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS))
                ->filter(static fn ($value) => !empty($value));
            if (in_array(
                $attribute,
                $filterables = $this->getFilterables($builder)
            ) and $items->isNotEmpty()) {
                $builder->whereIn($this->getTable($builder).'.'.$this->resolveAttribute(
                    $filterables,
                    $attribute
                ), $items);
            }
        }
    }
}
