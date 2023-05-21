<?php

	namespace Hans\Valravn\Services\Filtering\Filters;

	use Hans\Valravn\Services\Contracts\Filters\Filter;
	use Illuminate\Contracts\Database\Eloquent\Builder;

	class OrderFilter extends Filter {

		public function apply( Builder $builder, $values = null ) {
			$items = collect( $values ?? [] )
				->map( fn( $value ) => (string) $value )
				->map( fn( $value ) => filter_var( $value, FILTER_SANITIZE_FULL_SPECIAL_CHARS ) )
				->filter( fn( $value ) => strlen( $value ) > 0 and in_array( $value, [ 'asc', 'desc' ] ) );
			foreach ( $items as $attribute => $value ) {
				if ( in_array( $attribute,
						$filterables = $this->getFilterables( $builder ) ) and $items->isNotEmpty() ) {
					$builder->orderBy( $this->resolveAttribute( $filterables, $attribute ), $value );
				}
			}
		}
	}
