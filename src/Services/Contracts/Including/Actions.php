<?php

    namespace Hans\Valravn\Services\Contracts\Including;

    use Hans\Valravn\Models\Contracts\Filtering\Filterable;
    use Illuminate\Contracts\Database\Eloquent\Builder;

    abstract class Actions {

        public function __construct( private Builder $builder ) { }

        abstract function apply( array $params ): void;

        protected function getFilterableColumn( string $column ): string {
            if ( $this->builder()->getModel() instanceof Filterable ) {
                $filterable = $this->builder()->getModel()->getFilterableAttributes();
                if ( ( $position = array_search( $column, $filterable ) ) !== false ) {
                    return is_string( $position ) ? $position : $column;
                }
            }

            return $column;
        }

        protected function builder(): Builder {
            return $this->builder;
        }

    }
