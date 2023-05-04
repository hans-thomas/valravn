<?php

	namespace Hans\Valravn\Http\Resources\Contracts;

	use Hans\Valravn\Services\Includes\IncludingService;
	use Hans\Valravn\Services\Queries\QueryingService;
	use Countable;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\JsonResponse;
	use Illuminate\Http\Request;
	use Illuminate\Http\Resources\CollectsResources;
	use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
	use Illuminate\Pagination\AbstractCursorPaginator;
	use Illuminate\Pagination\AbstractPaginator;
	use Illuminate\Support\Collection;
	use IteratorAggregate;

	abstract class BaseResourceCollection extends BaseJsonResource implements Countable, IteratorAggregate {
		use CollectsResources;

		/**
		 * The resource that this resource collects.
		 *
		 * @var string
		 */
		public $collects;

		/**
		 * The mapped collection instance.
		 *
		 * @var Collection
		 */
		public $collection;

		/**
		 * Indicates if all existing request query parameters should be added to pagination links.
		 *
		 * @var bool
		 */
		protected $preserveAllQueryParameters = false;

		/**
		 * The query parameters that should be added to the pagination links.
		 *
		 * @var array|null
		 */
		protected $queryParameters;

		/**
		 * Create a new resource instance.
		 *
		 * @param mixed $resource
		 *
		 * @return void
		 */
		public function __construct( $resource ) {
			parent::__construct( $resource );
			$resource ??= [];
			$this->resource = $this->collectResource( $resource );
		}

		/**
		 * Transform the resource into an array.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		public function toArray( $request ): array {
			app( QueryingService::class, [ 'json_resource' => $this ] )
				->registerQueriesUsingQueryStringWhen( $this->shouldParseQueries(), $request->getQueryString() );

			// TODO: error possibility: $item might be a Model instance if there was not any resource class
			$response = $this->collection->map( function( BaseJsonResource $item ) use ( $request ) {
				$extracted = $this->extract( $item->resource ) ? : $item->extract( $item->resource ) ? : $item->resource->toArray();
				$data      = array_merge( [ 'type' => $this->type() ], $extracted );

				if ( $item->resource instanceof Model ) {
					app( IncludingService::class, [ 'json_resource' => $this ] )
						->registerIncludesUsingQueryStringWhen( $this->shouldParseIncludes(),
							$request->get( 'includes' ) )
						->applyRequestedIncludes( $item->resource )
						->mergeIncludedDataTo( $data );

					$this->loadedRelations( $data, $item );
					$this->loadedPivots( $data, $item );

					app( QueryingService::class, [ 'json_resource' => $this ] )
						->applyRequestedQueries( $item->resource )
						->mergeQueriedDataInto( $data );
				}

				$this->loaded( $data );

				if ( ! empty( $item->getExtra() ) ) {
					$data = array_merge( $data, [ 'extra' => $item->getExtra() ] );
				}

				return $data;
			} );

			app( QueryingService::class, [ 'json_resource' => $this ] )
				->applyRequestedCollectionQueries()
				->mergeQueriedData();
			$this->allLoaded();

			return $response->toArray();
		}

		/**
		 * Executes when all items loaded
		 *
		 */
		protected function allLoaded(): void {
			// ...
		}

		/**
		 * Indicate that all current query parameters should be appended to pagination links.
		 *
		 * @return $this
		 */
		public function preserveQuery() {
			$this->preserveAllQueryParameters = true;

			return $this;
		}

		/**
		 * Specify the query string parameters that should be present on pagination links.
		 *
		 * @param array $query
		 *
		 * @return $this
		 */
		public function withQuery( array $query ) {
			$this->preserveAllQueryParameters = false;

			$this->queryParameters = $query;

			return $this;
		}

		/**
		 * Return the count of items in the resource collection.
		 *
		 * @return int
		 */
		public function count(): int {
			return $this->collection->count();
		}

		/**
		 * Create an HTTP response that represents the object.
		 *
		 * @param Request $request
		 *
		 * @return JsonResponse
		 */
		public function toResponse( $request ) {
			if ( $this->resource instanceof AbstractPaginator || $this->resource instanceof AbstractCursorPaginator ) {
				return $this->preparePaginatedResponse( $request );
			}

			return parent::toResponse( $request );
		}

		/**
		 * Create a paginate-aware HTTP response.
		 *
		 * @param Request $request
		 *
		 * @return JsonResponse
		 */
		protected function preparePaginatedResponse( $request ) {
			if ( $this->preserveAllQueryParameters ) {
				$this->resource->appends( $request->query() );
			} elseif ( ! is_null( $this->queryParameters ) ) {
				$this->resource->appends( $this->queryParameters );
			}

			return ( new PaginatedResourceResponse( $this ) )->toResponse( $request );
		}

	}
