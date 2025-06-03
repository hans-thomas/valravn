<?php

namespace Hans\Valravn\Services\Includes\Actions;

use Hans\Valravn\Services\Contracts\Including\VActions;

class SelectAction extends VActions
{
    public function apply(array $params): void
    {
        $attributes = collect($params)->map(fn ($value) => $this->getFilterableColumn($value))
            ->filter(static fn ($value) => !is_null($value) && $value !== '');

        if ($attributes->isNotEmpty()) {
            $attributes[] = 'id';
            $this->builder()->select(...$attributes);
        }
    }
}
