<?php

namespace Hans\Valravn\DTOs;

    use Hans\Valravn\DTOs\Contracts\Dto;
    use Illuminate\Support\Collection;

    class BatchUpdateDto extends Dto
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
