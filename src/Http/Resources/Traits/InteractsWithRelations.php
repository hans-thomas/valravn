<?php

    namespace Hans\Valravn\Http\Resources\Traits;

    use Hans\Valravn\Models\Contracts\Filtering\Loadable;
    use Illuminate\Http\Resources\Json\JsonResource;

    trait InteractsWithRelations {
        public function loadedRelations( array &$data, JsonResource $resource = null ): void {
            $instance = $resource ?? $this;
            if ( $instance->resource instanceof Loadable ) {
                foreach ( $instance->resource->getLoadableRelations() as $loadable => $resource ) {
                    if ( $instance->resource->relationLoaded( $loadable ) ) {
                        $data[ $loadable ] = $resource::make( $instance->$loadable );
                    }
                }
            }
        }
    }
