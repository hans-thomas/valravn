<?php

	namespace Hans\Valravn\Services\Filtering;

	use Hans\Valravn\Services\Filtering\Filters\LikeFilter;
	use Hans\Valravn\Services\Filtering\Filters\OrderFilter;
	use Hans\Valravn\Services\Filtering\Filters\OrderPivotFilter;
	use Hans\Valravn\Services\Filtering\Filters\OrWhereRelationFilter;
	use Hans\Valravn\Services\Filtering\Filters\OrWhereRelationLikeFilter;
	use Hans\Valravn\Services\Filtering\Filters\WhereFilter;
	use Hans\Valravn\Services\Filtering\Filters\WherePivotFilter;
	use Hans\Valravn\Services\Filtering\Filters\WhereRelationFilter;
	use Hans\Valravn\Services\Filtering\Filters\WhereRelationLikeFilter;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Support\Arr;

	class FilteringService {
		private array $registered = [
			'like_filter'                   => LikeFilter::class,
			'order_filter'                  => OrderFilter::class,
			'order_pivot_filter'            => OrderPivotFilter::class,
			'where_filter'                  => WhereFilter::class,
			'where_pivot_filter'            => WherePivotFilter::class,
			'where_relation_filter'         => WhereRelationFilter::class,
			'where_relation_like_filter'    => WhereRelationLikeFilter::class,
			'or_where_relation_filter'      => OrWhereRelationFilter::class,
			'or_where_relation_like_filter' => OrWhereRelationLikeFilter::class,
		];

		public function apply( Builder $builder, array $options ): Builder {
			foreach ( $this->scopeActions( $options ) as $key => $filter ) {
				if ( request()->has( $key ) ) {
					call_user_func( [ new $filter(), 'apply' ], $builder, request()->input( $key ) );
				}
			}

			return $builder;
		}

		private function scopeActions( array $options ): array {
			$actions = $this->registered;
			if ( isset( $options[ 'only' ] ) ) {
				$actions = array_intersect( $actions, Arr::wrap( $options[ 'only' ] ) );
			}

			if ( isset( $options[ 'except' ] ) ) {
				$actions = array_diff( $actions, Arr::wrap( $options[ 'except' ] ) );
			}

			return $actions;
		}

		/**
		 * @return array
		 */
		public function getRegistered(): array {
			return $this->registered;
		}

		/**
		 * @param array $filters
		 *
		 * @return self
		 */
		public function setRegistered( array $filters ): self {
			$this->registered = array_merge( $this->getRegistered(), $filters );

			return $this;
		}


	}
