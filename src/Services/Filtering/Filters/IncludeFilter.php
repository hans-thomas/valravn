<?php

	namespace Hans\Valravn\Services\Filtering\Filters;

    use Hans\Valravn\Services\Contracts\Filters\Filter;
    use Illuminate\Contracts\Database\Eloquent\Builder;

    class IncludeFilter extends Filter {

        public function apply( Builder $builder, $values = null ) {
            $items = collect( explode( ',', $values ?? '' ) )
                ->map( fn( $value ) => (string) $value )
                ->filter( fn( $value ) => in_array( $value, $this->getLoadableRelations( $builder ) ) );
            if ( $items->isNotEmpty() ) {
                $builder->with( $items->toArray() );
            }
        }
    }
