<?php

namespace Hans\Valravn\Tests\Instances\DTOs;

use Hans\Valravn\DTOs\Contracts\VDto;
use Illuminate\Support\Collection;

class SampleDto extends VDto
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
        return collect($data['related'] ?? []);
    }
}
