<?php

namespace Hans\Valravn\Services\Filtering\Filters;

use Hans\Valravn\Services\Contracts\Filters\VFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class WherePivotVFilter extends VFilter
{
    public function apply(Builder $builder, $values = null)
    {
        if ($builder instanceof Relation) {
            foreach ($values as $attribute => $where) {
                $items = collect(explode(',', $where))->map(static fn($value) => filter_var(
                    $value,
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS
                ))->filter(static fn($value) => !empty($value));
                if (in_array($attribute, $this->getPivotFilterable($builder)) and $items->isNotEmpty()) {
                    $builder->wherePivotIn($attribute, $items);
                }
            }
        }
    }
}
