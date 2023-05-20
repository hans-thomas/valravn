<?php

	namespace Hans\Valravn\Http\Resources\Contracts;

	use Countable;
	use Hans\Valravn\Http\Resources\Traits\ResourceCollectionExtender;
	use Hans\Valravn\Services\Includes\IncludingService;
	use Hans\Valravn\Services\Queries\QueryingService;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Request;
	use IteratorAggregate;

	abstract class ValravnResourceCollection extends ValravnJsonResource implements Countable, IteratorAggregate {
		use ResourceCollectionExtender;

		/**
		 * Create a new resource instance.
		 *
		 * @param mixed $resource
		 *
		 * @return void
		 */
		public function __construct( $resource ) {
			parent::__construct( $resource );
			$resource       ??= [];
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
			app( QueryingService::class, [ 'resource' => $this ] )
				->registerQueriesUsingQueryStringWhen( $this->shouldParseQueries(), $request->getQueryString() );

			// TODO: error possibility: $item might be a Model instance if there was not any resource class
			$response = $this->collection->map( function( ValravnJsonResource $item ) use ( $request ) {
				$extracted = $this->extract( $item->resource ) ? : $item->extract( $item->resource ) ? : $item->resource->toArray();
				$data      = array_merge( [ 'type' => $this->type() ], $extracted );

				if ( $item->resource instanceof Model ) {
					app( IncludingService::class, [ 'resource' => $this ] )
						->registerIncludesUsingQueryStringWhen(
							$this->shouldParseIncludes(),
							$request->get( 'includes' )
						)
						->applyRequestedIncludes( $item->resource )
						->mergeIncludedDataTo( $data );

					$this->loadedRelations( $data, $item );
					$this->loadedPivots( $data, $item );

					app( QueryingService::class, [ 'resource' => $this ] )
						->applyRequestedQueries( $item->resource )
						->mergeQueriedDataInto( $data );
				}

				$this->loaded( $data );

				if ( ! empty( $item->getExtra() ) ) {
					$data = array_merge( $data, [ 'extra' => $item->getExtra() ] );
				}

				return $data;
			} );

			app( QueryingService::class, [ 'resource' => $this ] )
				->applyRequestedCollectionQueries()
				->mergeCollectionQueriedData();
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

	}
