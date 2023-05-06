<?php

	namespace Hans\Valravn\Services\Queries;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\CollectionQuery;
	use Hans\Valravn\Http\Resources\Contracts\ResourceQuery;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Str;

	class QueryingService {
		private BaseJsonResource $resource;
		private array $executedQueries = [];

		public function __construct( BaseJsonResource $resource ) {
			$this->resource = $resource;
		}

		public function registerQueriesUsingQueryString( string|array|null $queries ): self {
			if ( is_null( $queries ) ) {
				return $this;
			}
			if ( is_string( $queries ) ) {
				$queries = explode( '&', $queries );
			}
			foreach ( $queries as $index => $value ) {
				if ( Str::startsWith( $value, 'with_' ) ) {
					$queries[ $index ] = Str::of( $value )
					                        ->replaceLast( '=', '' )
					                        ->snake()
					                        ->toString();
				} else {
					unset( $queries[ $index ] );
				}
			}

			foreach ( array_unique( $queries ) as $query ) {
				if ( key_exists( $query, $availableQueries = $this->resource->getAvailableQueries() ) ) {
					$this->resource->registerQuery( $availableQueries [ $query ] );
				}
			}

			return $this;
		}

		public function registerQueriesUsingQueryStringWhen( bool $condition, string|array|null $queries ): self {
			if ( $condition ) {
				$this->registerQueriesUsingQueryString( $queries );
			}

			return $this;
		}

		public function applyRequestedQueries( Model $model ): self {
			foreach ( $this->resource->getRequestedQueries() as $query ) {
				if ( ! is_a( $query, CollectionQuery::class, true ) ) {
					$this->executedQueries[] = app( $query )->run( $model );
				}
			}

			return $this;
		}

		public function applyRequestedCollectionQueries(): self {
			foreach ( $this->resource->getRequestedQueries() as $query ) {
				if ( is_a( $query, CollectionQuery::class, true ) ) {
					$this->executedQueries[] = app( $query )->run( $this->resource );
				}
			}

			return $this;
		}

		public function mergeQueriedDataInto( array &$data ): void {
			foreach ( $this->getExecutedQueries() as $query ) {
				if ( is_a( $query, ResourceQuery::class ) ) {
					$query->mergeDataInto( $this->resource, $data );
				}
			}
		}

		public function getQueriedData(): array {
			$data = [];
			foreach ( $this->getExecutedQueries() as $query ) {
				if ( is_a( $query, ResourceQuery::class ) ) {
					$data = array_merge( $data, $query->getData() );
				}
			}

			return $data;
		}

		public function mergeCollectionQueriedData(): void {
			foreach ( $this->getExecutedQueries() as $query ) {
				if ( is_a( $query, CollectionQuery::class ) ) {
					$query->mergeDataInto( $this->resource );
				}
			}
		}

		protected function getExecutedQueries(): array {
			return $this->executedQueries;
		}
	}
