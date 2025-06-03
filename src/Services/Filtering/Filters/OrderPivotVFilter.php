<?php

namespace Hans\Valravn\Services\Filtering\Filters;

use Hans\Valravn\Services\Contracts\Filters\VFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class OrderPivotVFilter extends VFilter
{
    public function apply(Builder $builder, $values = null)
    {
        $items = collect($values ?? [])
            ->map(static fn($value) => (string)$value)
            ->map(static fn($value) => filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            ->filter(static fn($value) => in_array($value, ['asc', 'desc']))
            ->filter(fn($value, $attribute) => in_array($attribute, $this->getPivotFilterable($builder)));
        if ($items->isNotEmpty()) {
            $builder->reorder();
        }
        foreach ($items as $attribute => $value) {
            $builder->orderByPivot($attribute, $value);
        }
    }
}
