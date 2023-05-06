<?php

	namespace Hans\Valravn\Services\Includes;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\Includes;
	use Hans\Valravn\Services\Includes\Actions\LimitAction;
	use Hans\Valravn\Services\Includes\Actions\OrderAction;
	use Hans\Valravn\Services\Includes\Actions\SelectAction;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Resources\Json\JsonResource;
	use Illuminate\Support\Str;

	class IncludingService {
		private BaseJsonResource $resource;
		private array $data = [];
		private array $registeredActions = [
			'select' => SelectAction::class,
			'order'  => OrderAction::class,
			'limit'  => LimitAction::class,
		];

		/**
		 * @param BaseJsonResource $resource
		 */
		public function __construct( BaseJsonResource $resource ) {
			$this->resource = $resource;
		}

		/**
		 * @param string|array|null $includes
		 *
		 * @return $this
		 */
		public function registerIncludesUsingQueryString( string|array|null $includes ): self {
			if ( is_null( $includes ) ) {
				return $this;
			}

			if ( is_string( $includes ) ) {
				$includes = explode( ',', $includes );
			}

			foreach ( $includes as $include ) {
				$data = $this->parseInclude( $include );
				if ( is_null( $data ) ) {
					continue;
				}

				if ( ! empty( $data[ 'nested' ] ) ) {
					$this->resource->setNestedEagerLoadsFor( $data[ 'relation' ], $data[ 'nested' ] );
				}
				$this->resource
					->registerInclude(
						$this->getAvailableIncludes()[ $data[ 'relation' ] ],
						$data[ 'actions' ]
					);

			}

			return $this;
		}

		public function registerIncludesUsingQueryStringWhen( bool $condition, string|array|null $includes ): self {
			if ( $condition ) {
				$this->registerIncludesUsingQueryString( $includes );
			}

			return $this;
		}

		public function parseInclude( string $include ): array {
			$relation           = Str::of( $include )->before( ':' )->before( '.' )->toString();
			$data[ 'relation' ] = $relation;
			$data[ 'actions' ]  = [];
			$data[ 'nested' ]   = [];
			if ( ! key_exists( $relation, $this->getAvailableIncludes() ) ) {
				return $data;
			}
			// nested data
			$nested           = Str::of( $include )
			                       ->substr( Str::of( $include )->before( '.' )->length() )
			                       ->after( '.' )
			                       ->toString();
			$data[ 'nested' ] = $nested;
			// actions data
			$filters = Str::of( $include )
			              ->replace( "$relation.", '' )
			              ->replace( $data[ 'nested' ], '' )
			              ->before( '.' )
			              ->explode( ':' );
			foreach ( $filters as $filter ) {
				$action = Str::before( $filter, '(' );
				$params = Str::of( $filter )
				             ->substr( strlen( $action ) )
				             ->before( '.' )
				             ->trim( '()' )
				             ->explode( '|' )
				             ->toArray();

				if ( ! key_exists( $action, $this->registeredActions ) ) {
					continue;
				}
				$action                       = $this->registeredActions[ $action ];
				$data[ 'actions' ][ $action ] = $params;
			}

			return $data;
		}

		public function applyRequestedIncludes( Model $model ): self {
			foreach ( $this->resource->getRequestedIncludes() as $include => $actions ) {
				$this->data[ $this->getInstanceKey( $include ) ] = app( $include )->run( $model )
				                                                                  ->registerActions( $actions )
				                                                                  ->applyActions()
				                                                                  ->toResource();
				if ( key_exists( $this->getInstanceKey( $include ), $this->resource->getNestedEagerLoads() ) ) {
					$this->data[ $this->getInstanceKey( $include ) ]
						->applyNestedEagerLoadsOnRelation(
							$this->resource->getNestedEagerLoadsFor( $this->getInstanceKey( $include ) )
						);
				}
			}

			return $this;
		}

		protected function getAvailableIncludes(): array {
			return $this->resource->getAvailableIncludes();
		}

		protected function getIncludeInstance( string $include ): Includes {
			return app( $this->getAvailableIncludes()[ $include ] );
		}

		protected function getInstanceKey( string|object $instance ): string {
			$instance = is_object( $instance ) ? get_class( $instance ) : $instance;

			return array_flip( $this->getAvailableIncludes() )[ $instance ];
		}

		/**
		 * @return array
		 */
		public function getIncludedData(): array {
			return $this->data;
		}

		public function mergeIncludedDataTo( array &$data ): void {
			$data = array_merge( $data, $this->getIncludedData() );
		}

		/**
		 * @return array
		 */
		public function getRegisteredActions(): array {
			return $this->registeredActions;
		}

		/**
		 * @param array $actions
		 *
		 * @return self
		 */
		public function registerActions( array $actions ): self {
			$this->registeredActions = array_merge( $this->getRegisteredActions(), $actions );

			return $this;
		}


	}
