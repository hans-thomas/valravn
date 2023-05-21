<?php

	namespace Hans\Valravn\Services\Filtering\Filters;

	use Hans\Valravn\Services\Contracts\Filters\Filter;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Relations\Relation;

	class WherePivotFilter extends Filter {

		public function apply( Builder $builder, $values = null ) {
			if ( $builder instanceof Relation ) {
				foreach ( $values as $attribute => $where ) {
					$items = collect( explode( ',', $where ) )->map( fn( $value ) => filter_var( $value,
						FILTER_SANITIZE_FULL_SPECIAL_CHARS ) )->filter( fn( $value ) => ! empty( $value ) );
					if ( in_array( $attribute, $this->getPivotFilterable( $builder ) ) and $items->isNotEmpty() ) {
						$builder->wherePivotIn( $attribute, $items );
					}
				}
			}
		}
	}
