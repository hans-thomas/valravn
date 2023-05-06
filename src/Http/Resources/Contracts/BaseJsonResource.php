<?php

    namespace Hans\Valravn\Http\Resources\Contracts;

    use Hans\Valravn\Http\Resources\Traits\InteractsWithPivots;
    use Hans\Valravn\Http\Resources\Traits\InteractsWithRelations;
    use Hans\Valravn\Services\Includes\IncludingService;
    use Hans\Valravn\Services\Queries\QueryingService;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    abstract class BaseJsonResource extends JsonResource {
        use InteractsWithRelations, InteractsWithPivots;

        private array $extra = [];
        protected bool $includes = false;
        protected bool $queries = false;
        protected array $requested_queries = [];
        protected array $requested_includes = [];
        private array $requested_eager_loads = [];

        /**
         * Extract attributes of the given resource
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
        public function toArray( $request ): array {
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

            return $data;
        }

        /**
         * Executes when data loaded
         *
         * @param $data
         */
        protected function loaded( &$data ) {
            // ...
        }

        public function with( $request ) {
            return [
                'type' => $this->type(),
            ];
        }

        public function addAdditional( array $data ): self {
            $this->additional = array_merge( $this->additional, $data );

            return $this;
        }

        public function addExtra( array $data ): self {
            $this->extra = array_merge( $this->extra, $data );

            return $this;
        }

        public function getExtra(): array {
            return $this->extra;
        }

        public function parseIncludes(): self {
            $this->includes = true;

            return $this;
        }

        public function parseIncludesWhen( bool $condition ): self {
            if ( $condition ) {
                $this->parseIncludes();
            }

            return $this;
        }

        public function shouldParseIncludes(): bool {
            return $this->includes;
        }

        public function registerInclude( string|object $include, array $actions = [] ): self {
            $include = is_object( $include ) ? get_class( $include ) : $include;
            if ( in_array( $include, $this->getAvailableIncludes() ) ) {
                $this->addRequestedIncludes( $include, $actions );
            }

            return $this;
        }

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

        public function getRequestedIncludes(): array {
            return $this->requested_includes;
        }

        public function setNestedEagerLoadsFor( string $include, string $eagerLoads ): self {
            $this->requested_eager_loads[ $include ] = $eagerLoads;

            return $this;
        }

        public function getNestedEagerLoadsFor( string $relation ): ?string {
            if ( ! key_exists( $relation, $this->getNestedEagerLoads() ) ) {
                return null;
            }

            return $this->requested_eager_loads[ $relation ];
        }

        public function getNestedEagerLoads(): array {
            return $this->requested_eager_loads;
        }

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

        public function parseQueries(): self {
            $this->queries = true;

            return $this;
        }

        public function parseQueriesWhen( bool $condition ): self {
            if ( $condition ) {
                $this->parseQueries();
            }

            return $this;
        }

        public function shouldParseQueries(): bool {
            return $this->queries;
        }

        public function registerQuery( string|object $query ): self {
            $query = is_object( $query ) ? get_class( $query ) : $query;
            if ( in_array( $query, $this->getAvailableQueries() ) ) {
                $this->addRequestedQueries( $query );
            }

            return $this;
        }

        public function registerQueries( array $queries ): self {
            foreach ( $queries as $query ) {
                $this->registerQuery( $query );
            }

            return $this;
        }

        public function getRequestedQueries(): array {
            return $this->requested_queries;
        }

        protected function addRequestedQueries( string $query ): self {
            if ( ! in_array( $query, $this->getRequestedQueries() ) ) {
                $this->requested_queries = array_merge( $this->getRequestedQueries(), [ $query ] );
            }

            return $this;
        }

        public function hasRequestedQuery(): bool {
            return count( $this->getRequestedQueries() );
        }

    }
