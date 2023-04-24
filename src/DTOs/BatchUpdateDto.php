<?php

    namespace Hans\Valravn\DTOs;

    use Hans\Valravn\DTOs\Contracts\Dto;
    use Illuminate\Support\Collection;

    class BatchUpdateDto extends Dto {

        protected function parse( array $data ): Collection {
            if ( ! isset( $data[ 'batch' ] ) ) {
                return collect();
            }

            return collect( $data[ 'batch' ] )->reverse()->unique('id')->reverse();
        }
    }
