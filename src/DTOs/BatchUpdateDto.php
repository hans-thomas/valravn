<?php

namespace Hans\Valravn\DTOs;

use Hans\Valravn\DTOs\Contracts\VDto;
use Illuminate\Support\Collection;

class BatchUpdateDto extends VDto
{
    /**
     * Process the received data.
     *
     * @param array $data
     *
     * @return Collection
     */
    protected function parse(array $data): Collection
    {
        if (!isset($data['batch'])) {
            return collect();
        }

        return collect($data['batch'])->reverse()->unique('id')->reverse();
    }

    /** @inheritDoc */
    public static function makeFromArray(array|Collection $data): static
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;

        return new self($data);
    }

    /** @inheritDoc */
    public static function getKeyName(): string
    {
        return 'batch';
    }
}
