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
		private array $registeredFilters;

		public function __construct() {
			$this->registeredFilters = valravn_config( 'filters' );
		}

		public function apply( Builder $builder, array $options = [] ): Builder {
			foreach ( $this->scopeActions( $options ) as $key => $filter ) {
				if ( request()->has( $key ) ) {
					call_user_func( [ new $filter(), 'apply' ], $builder, request()->input( $key ) );
				}
			}

			return $builder;
		}

		private function scopeActions( array $options ): array {
			$actions = $this->registeredFilters;
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
			return $this->registeredFilters;
		}

	}
