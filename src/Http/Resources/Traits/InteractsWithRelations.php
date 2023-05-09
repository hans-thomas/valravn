<?php

	namespace Hans\Valravn\Http\Resources\Traits;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\Models\Contracts\Filtering\Loadable;

	trait InteractsWithRelations {

		/**
		 * Load relationships data if exists
		 *
		 * @param array                 $data
		 * @param BaseJsonResource|null $resource
		 *
		 * @return void
		 */
		protected function loadedRelations( array &$data, BaseJsonResource $resource = null ): void {
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
