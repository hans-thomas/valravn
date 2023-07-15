<?php

	namespace Hans\Valravn\Http\Resources\Contracts;

	use Hans\Valravn\Http\Resources\Traits\InteractsWithPivots;
	use Hans\Valravn\Http\Resources\Traits\InteractsWithRelations;
	use Hans\Valravn\Services\Includes\IncludingService;
	use Hans\Valravn\Services\Queries\QueryingService;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Request;
	use Illuminate\Http\Resources\Json\JsonResource;

	abstract class ValravnJsonResource extends JsonResource {
		use InteractsWithRelations, InteractsWithPivots;

		/**
		 * Store extra data for resource
		 *
		 * @var array
		 */
		private array $extra = [];

		/**
		 * Determine should execute includes
		 *
		 * @var bool
		 */
		protected bool $includes = false;

		/**
		 * Keep requested includes to execute in the future
		 *
		 * @var array
		 */
		protected array $requested_includes = [];

		/**
		 * Determine should execute queries
		 *
		 * @var bool
		 */
		protected bool $queries = false;

		/**
		 * Keep requested queries to execute in the future
		 *
		 * @var array
		 */
		protected array $requested_queries = [];

		/**
		 * Keep requested eager loads to execute in the future
		 *
		 * @var array
		 */
		private array $requested_eager_loads = [];


		/**
		 * Store the list of fields that must only be in response
		 *
		 * @var array
		 */
		private array $only = [];

		/**
		 * Extract attributes of the given model
		 * if null returned, the parent::toArray method called by default
		 *
		 * @param Model $model
		 *
		 * @return array|null
		 */
		abstract public function extract( Model $model ): ?array;

		/**
		 * Specify the type of your resource
		 *
		 * @return string
		 */
		abstract public function type(): string;

		/**
		 * List of available queries of this resource
		 *
		 * @return array
		 */
		public function getAvailableQueries(): array {
			return [
				//
			];
		}

		/**
		 * List of available includes of this resource
		 *
		 * @return array
		 */
		public function getAvailableIncludes(): array {
			return [
				//
			];
		}

		/**
		 * Transform the resource into an array.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		public function toArray( Request $request ): array {
			if ( is_null( $this->resource ) ) {
				return [];
			}

			$extracted = $this->extract( $this->resource );
			if ( count( $extracted ?? [] ) <= 0 ) {
				$extracted = $this->resource->toArray();
			}
			$data = array_merge( [ 'type' => $this->type() ], $extracted );

			if ( $this->resource instanceof Model ) {
				app( IncludingService::class, [ 'resource' => $this ] )
					->registerIncludesUsingQueryStringWhen( $this->shouldParseIncludes(), $request->get( 'includes' ) )
					->applyRequestedIncludes( $this->resource )
					->mergeIncludedDataTo( $data );

				$this->loadedRelations( $data );
				$this->loadedPivots( $data );

				app( QueryingService::class, [ 'resource' => $this ] )
					->registerQueriesUsingQueryStringWhen( $this->shouldParseQueries(), $request->getQueryString() )
					->applyRequestedQueries( $this->resource )
					->mergeQueriedDataInto( $data );
			}

			if ( ! empty( $this->getExtra() ) ) {
				$data = array_merge( $data, [ 'extra' => $this->getExtra() ] );
			}

			$this->loaded( $data );

			$this->applyOnly( $data );

			return $data;
		}

		/**
		 * Executes when data loaded
		 *
		 * @param $data
		 *
		 * @return void
		 */
		protected function loaded( &$data ): void {
			// ...
		}

		/**
		 * Get any additional data that should be returned with the resource array.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		public function with( Request $request ): array {
			return [
				'type' => $this->type(),
			];
		}

		/**
		 * Merge data to the additional
		 *
		 * @param array $data
		 *
		 * @return $this
		 */
		public function addAdditional( array $data ): self {
			$this->additional = array_merge( $this->additional, $data );

			return $this;
		}

		/**
		 * Merge data to the extra
		 *
		 * @param array $data
		 *
		 * @return $this
		 */
		public function addExtra( array $data ): self {
			$this->extra = array_merge( $this->extra, $data );

			return $this;
		}

		/**
		 * Return extra data
		 *
		 * @return array
		 */
		public function getExtra(): array {
			return $this->extra;
		}

		/**
		 * Parse includes on this resource instance
		 *
		 * @return $this
		 */
		public function parseIncludes(): self {
			$this->includes = true;

			return $this;
		}

		/**
		 * Parse includes on this resource instance when condition is true
		 *
		 * @param bool $condition
		 *
		 * @return $this
		 */
		public function parseIncludesWhen( bool $condition ): self {
			if ( $condition ) {
				$this->parseIncludes();
			}

			return $this;
		}

		/**
		 * Determine that resource should parse the includes or not
		 *
		 * @return bool
		 */
		public function shouldParseIncludes(): bool {
			return $this->includes;
		}

		/**
		 * Manually register an include class
		 *
		 * @param string|object $include
		 * @param array         $actions
		 *
		 * @return $this
		 */
		public function registerInclude( string|object $include, array $actions = [] ): self {
			$include = is_object( $include ) ? get_class( $include ) : $include;
			if ( in_array( $include, $this->getAvailableIncludes() ) ) {
				$this->addRequestedIncludes( $include, $actions );
			}

			return $this;
		}

		/**
		 * Register the given include to requested includes list
		 *
		 * @param string $include
		 * @param array  $actions
		 *
		 * @return $this
		 */
		protected function addRequestedIncludes( string $include, array $actions ): self {
			if ( key_exists( $include, $this->getRequestedIncludes() ) ) {
				$this->requested_includes[ $include ] = array_merge(
					$this->requested_includes[ $include ],
					$actions
				);
			}
			if ( ! key_exists( $include, $this->getRequestedIncludes() ) ) {
				$this->requested_includes[ $include ] = $actions;
			}

			return $this;
		}

		/**
		 * Return the requested includes list
		 *
		 * @return array
		 */
		public function getRequestedIncludes(): array {
			return $this->requested_includes;
		}

		/**
		 * Set a nested eager load for current instance
		 *
		 * @param string $include
		 * @param string $eagerLoads
		 *
		 * @return $this
		 */
		public function setNestedEagerLoadsFor( string $include, string $eagerLoads ): self {
			$this->requested_eager_loads[ $include ] = $eagerLoads;

			return $this;
		}

		/**
		 * Return requested nested eager load for a specific relationship
		 *
		 * @param string $relation
		 *
		 * @return string|null
		 */
		public function getNestedEagerLoadsFor( string $relation ): ?string {
			if ( ! key_exists( $relation, $this->getNestedEagerLoads() ) ) {
				return null;
			}

			return $this->requested_eager_loads[ $relation ];
		}

		/**
		 * Return the requested nested eager loads list
		 *
		 * @return array
		 */
		public function getNestedEagerLoads(): array {
			return $this->requested_eager_loads;
		}

		/**
		 * Apply a nested eager load on the current instance
		 *
		 * @param string $nested
		 *
		 * @return $this
		 */
		public function applyNestedEagerLoadsOnRelation( string $nested ): self {
			$data = app( IncludingService::class, [ 'resource' => $this ] )->parseInclude( $nested );

			if ( ! empty( $data[ 'nested' ] ) ) {
				$this->setNestedEagerLoadsFor( $data[ 'relation' ], $data[ 'nested' ] );
			}

			if ( key_exists( $data[ 'relation' ], $this->getAvailableIncludes() ) ) {
				$this->registerInclude( $this->getAvailableIncludes()[ $data[ 'relation' ] ], $data[ 'actions' ] );
			}

			return $this;
		}

		/**
		 * Parse queries on this resource instance
		 *
		 * @return $this
		 */
		public function parseQueries(): self {
			$this->queries = true;

			return $this;
		}

		/**
		 * Parse queries on this resource instance when condition is true
		 *
		 * @param bool $condition
		 *
		 * @return $this
		 */
		public function parseQueriesWhen( bool $condition ): self {
			if ( $condition ) {
				$this->parseQueries();
			}

			return $this;
		}

		/**
		 * Determine that resource should parse the queries or not
		 *
		 * @return bool
		 */
		public function shouldParseQueries(): bool {
			return $this->queries;
		}

		/**
		 * Manually register a query class
		 *
		 * @param string|object $query
		 *
		 * @return $this
		 */
		public function registerQuery( string|object $query ): self {
			$query = is_object( $query ) ? get_class( $query ) : $query;
			if ( in_array( $query, $this->getAvailableQueries() ) ) {
				$this->addRequestedQueries( $query );
			}

			return $this;
		}

		/**
		 * Manually register several query classes
		 *
		 * @param array $queries
		 *
		 * @return $this
		 */
		public function registerQueries( array $queries ): self {
			foreach ( $queries as $query ) {
				$this->registerQuery( $query );
			}

			return $this;
		}

		/**
		 * Return the requested queries list
		 *
		 * @return array
		 */
		public function getRequestedQueries(): array {
			return $this->requested_queries;
		}

		/**
		 * Register the given include to requested queries list
		 *
		 * @param string $query
		 *
		 * @return $this
		 */
		protected function addRequestedQueries( string $query ): self {
			if ( ! in_array( $query, $this->getRequestedQueries() ) ) {
				$this->requested_queries = array_merge( $this->getRequestedQueries(), [ $query ] );
			}

			return $this;
		}

		/**
		 * Determine any query requested on this instance or not
		 *
		 * @return bool
		 */
		public function hasAnyRequestedQuery(): bool {
			return count( $this->getRequestedQueries() );
		}

		/**
		 * Return only given fields in response
		 *
		 * @param array|string $fields
		 *
		 * @return $this
		 */
		public function only( array|string $fields ): self {
			$this->only = is_array( $fields ) ? $fields : func_get_args();

			return $this;
		}

		protected function applyOnly( array &$data ): void {
			if ( ! empty( $this->only ) ) {
				$data = array_intersect_key(
					$data,
					[ 'id' => - 1, 'type' => - 2 ] + array_flip( $this->only )
				);
			}
		}

	}
