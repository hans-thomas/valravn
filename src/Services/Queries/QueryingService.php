<?php

    namespace Hans\Valravn\Services\Queries;

    use Hans\Valravn\Http\Resources\Contracts\CollectionQuery;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Resources\Json\JsonResource;
    use Illuminate\Support\Str;

    class QueryingService {
        private JsonResource $json_resource;
        private array $executedQueries;

        public function __construct( JsonResource $json_resource ) {
            $this->json_resource = $json_resource;
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
                if ( key_exists( $query, $availableQueries = $this->json_resource->getAvailableQueries() ) ) {
                    $this->json_resource->registerQuery( $availableQueries [ $query ] );
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
            foreach ( $this->json_resource->getRequestedQueries() as $query ) {
                if ( ! is_a( $query, CollectionQuery::class, true ) ) {
                    $this->executedQueries[] = app( $query )->run( $model );
                }
            }

            return $this;
        }

        public function applyRequestedCollectionQueries(): self {
            foreach ( $this->json_resource->getRequestedQueries() as $query ) {
                if ( is_a( $query, CollectionQuery::class, true ) ) {
                    $this->executedQueries[] = app( $query )->run( $this->json_resource );
                }
            }

            return $this;
        }

        public function mergeQueriedDataInto( array &$data ): void {
            foreach ( $this->getExecutedQueries() as $query ) {
                if ( ! is_a( $query, CollectionQuery::class ) ) {
                    $query->mergeDataInto( $this->json_resource, $data );
                }
            }
        }

        public function mergeQueriedData(): void {
            foreach ( $this->getExecutedQueries() as $query ) {
                if ( is_a( $query, CollectionQuery::class ) ) {
                    $query->mergeDataInto( $this->json_resource );
                }
            }
        }

        protected function getExecutedQueries(): array {
            if ( ! isset( $this->executedQueries ) ) {
                return [];
            }

            return $this->executedQueries;
        }
    }
