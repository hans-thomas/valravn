<?php

    namespace Hans\Valravn\Http\Resources\Traits;

    use Illuminate\Http\Resources\Json\JsonResource;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;

    trait InteractsWithPivots {
        public function loadedPivots( array &$data, JsonResource $resource = null, array $excludes = [], array $alias = [] ): void {
            $instance = $resource ?? $this;
            if ( isset( $instance->resource->pivot ) ) {
                $data[ 'pivot' ] = collect( $instance->resource->pivot->getAttributes() )
                    ->filter( fn( $value, $key ) => in_array( $key, $excludes ) or ! Str::contains( $key,
                            [ '_id', '_type' ] ) )
                    ->mapWithKeys( fn( $value, $key ) => ( $index = array_search( $key,
                        array_keys( $alias ) ) ) !== false ? [
                        Arr::get( array_values( $alias ), $index ) => $value
                    ] : [ $key => $value ] )
                    ->toArray();
            }
        }
    }
