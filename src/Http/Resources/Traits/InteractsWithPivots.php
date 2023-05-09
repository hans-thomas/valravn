<?php

	namespace Hans\Valravn\Http\Resources\Traits;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Support\Arr;
	use Illuminate\Support\Str;

	trait InteractsWithPivots {

		/**
		 * Load pivots if exists
		 *
		 * @param array                 $data
		 * @param BaseJsonResource|null $resource
		 * @param array                 $excludes
		 * @param array                 $alias
		 *
		 * @return void
		 */
		protected function loadedPivots( array &$data, BaseJsonResource $resource = null, array $excludes = [], array $alias = [] ): void {
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
