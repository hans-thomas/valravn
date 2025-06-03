<?php

namespace Hans\Valravn\Services\Filtering\Filters;

use Hans\Valravn\Services\Contracts\Filters\VFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LikeVFilter extends VFilter
{
    public function apply(Builder $builder, $values = null)
    {
        $items = collect($values ?? [])
            ->map(static fn($value) => (string)$value)
            ->map(static fn($value) => filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            ->filter(static fn($value) => strlen($value) > 0);
        foreach ($items as $attribute => $value) {
            if (
                in_array($attribute, $filterables = $this->getFilterables($builder)) and
                $items->isNotEmpty()
            ) {
                $builder->whereLike(
                    $this->getTable($builder) . '.' . $this->resolveAttribute($filterables, $attribute),
                    $value
                );
            }
        }
    }
}
