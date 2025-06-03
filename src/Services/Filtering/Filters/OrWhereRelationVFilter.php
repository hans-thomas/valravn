<?php

namespace Hans\Valravn\Services\Filtering\Filters;

use Hans\Valravn\Services\Contracts\Filters\VFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class OrWhereRelationVFilter extends VFilter
{
    public function apply(Builder $builder, $values = null)
    {
        $items = collect($values ?? [])
            ->map(static fn ($value) => (string) $value)
            ->map(static fn ($value) => filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            ->filter(fn ($value, $index) => strlen($value) > 0 and in_array(
                Str::before($index, '->'),
                $this->getLoadableRelations($builder)
            ));
        foreach ($items as $attribute => $value) {
            $relation = Str::before($attribute, '->');
            $column = Str::after($attribute, '->');
            $builder->orWhereHas($relation, static fn (Builder $whereHas) => $whereHas->where($column, $value));
        }
    }
}
