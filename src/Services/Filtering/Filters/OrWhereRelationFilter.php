<?php

    namespace Hans\Valravn\Services\Filtering\Filters;

    use Hans\Valravn\Services\Contracts\Filters\Filter;
    use Illuminate\Contracts\Database\Eloquent\Builder;
    use Illuminate\Support\Str;

    class OrWhereRelationFilter extends Filter {

        public function apply( Builder $builder, $values = null ) {
            $items = collect( $values ?? [] )
                ->map( fn( $value ) => (string) $value )
                ->map( fn( $value ) => filter_var( $value, FILTER_SANITIZE_FULL_SPECIAL_CHARS ) )
                ->filter( fn( $value, $index ) => strlen( $value ) > 0 and in_array( Str::before( $index, '->' ),
                        $this->getLoadableRelations( $builder ) ) );
            foreach ( $items as $attribute => $value ) {
                $relation = Str::before( $attribute, '->' );
                $column   = Str::after( $attribute, '->' );
                $builder->orWhereHas( $relation, fn( Builder $whereHas ) => $whereHas->where( $column, $value ) );
            }
        }
    }
