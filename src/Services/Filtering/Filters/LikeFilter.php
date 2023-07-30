<?php

namespace Hans\Valravn\Services\Filtering\Filters;

    use Hans\Valravn\Services\Contracts\Filters\Filter;
    use Illuminate\Contracts\Database\Eloquent\Builder;

    class LikeFilter extends Filter
    {
        public function apply(Builder $builder, $values = null)
        {
            $items = collect($values ?? [])
                ->map(fn ($value) => (string) $value)
                ->map(fn ($value) => filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS))
                ->filter(fn ($value) => strlen($value) > 0);
            foreach ($items as $attribute => $value) {
                if (
                    in_array($attribute, $filterables = $this->getFilterables($builder)) and
                    $items->isNotEmpty()
                ) {
                    $builder->whereLike(
                        $this->getTable($builder).'.'.$this->resolveAttribute($filterables, $attribute),
                        $value
                    );
                }
            }
        }
    }
