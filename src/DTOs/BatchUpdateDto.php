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
}
