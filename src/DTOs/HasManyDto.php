<?php

namespace Hans\Valravn\DTOs;

use Hans\Valravn\DTOs\Contracts\Dto;
use Illuminate\Support\Collection;

class HasManyDto extends Dto
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
        $output = [];
        if (!isset($data['related'])) {
            return collect();
        }
        foreach ($data['related'] as $item) {
            $output[] = $item['id'];
        }

        return collect($output)->unique();
    }
}
