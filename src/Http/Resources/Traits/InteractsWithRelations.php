<?php

	namespace Hans\Valravn\Http\Resources\Traits;

	use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
	use Hans\Valravn\Models\Contracts\Loadable;
	use Illuminate\Support\Arr;

	trait InteractsWithRelations {

		/**
		 * Override resource class resolver for specific relationship
		 *
		 * @var array
		 */
		private array $relationResolvers = [];

		/**
		 * Skip a loaded relationship to be printed in response
		 *
		 * @var array
		 */
		private array $skipRelationsForModel = [];

		/**
		 * Load relationships data if exists
		 *
		 * @param array                    $data
		 * @param ValravnJsonResource|null $resource
		 *
		 * @return void
		 */
		protected function loadedRelations( array &$data, ValravnJsonResource $resource = null ): void {
			$instance = $resource ?? $this;
			if ( $instance->resource instanceof Loadable ) {
				foreach ( $instance->resource->getLoadableRelations() as $loadable => $resource ) {
					if ( $instance->resource->relationLoaded( $loadable ) ) {
						if ( in_array( $loadable, array_keys( $this->relationResolvers ) ) ) {
							$resource = $this->relationResolvers[ $loadable ];
						}

						if (
							in_array(
								$resourceClass = get_class( $instance->resource ),
								array_keys( $this->skipRelationsForModel )
							) and
							in_array( $loadable, Arr::wrap( $this->skipRelationsForModel[ $resourceClass ] ) )
						) {
							continue;
						}

						$data[ $loadable ] = $resource::make( $instance->$loadable )
						                              ->resolveRelationsUsing( $this->relationResolvers )
						                              ->skipRelationsForModel( $this->skipRelationsForModel );
					}
				}
			}
		}

		/**
		 * Accept relations and their custom resource class
		 *
		 * @param array<string,class-string> $resolvers
		 *
		 * @return static
		 */
		public function resolveRelationsUsing( array $resolvers ): static {
			$this->relationResolvers = array_merge( $this->relationResolvers, $resolvers );

			return $this;
		}

		/**
		 * Accept models and their models that should be skipped
		 *
		 * @param array<class-string,string|array> $resolvers
		 *
		 * @return static
		 */
		public function skipRelationsForModel( array $resolvers ): static {
			$this->skipRelationsForModel = array_merge( $this->skipRelationsForModel, $resolvers );

			return $this;
		}

	}
