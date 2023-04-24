<?php

    namespace Hans\Valravn\DTOs;

    use Hans\Valravn\DTOs\Contracts\Dto;
    use Illuminate\Support\Collection;

    class MorphToDto extends Dto {

        protected function parse( array $data ): Collection {
            if ( ! isset( $data[ 'related' ] ) ) {
                return collect();
            }

            return collect( $data[ 'related' ][ 'entity' ] ?? [] );
        }
    }
