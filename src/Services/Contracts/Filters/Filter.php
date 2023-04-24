<?php

    namespace Hans\Valravn\Services\Contracts\Filters;


    use Hans\Valravn\Models\Contracts\Filtering\Filterable;
    use Hans\Valravn\Models\Contracts\Filtering\Loadable;
    use Illuminate\Contracts\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\Relation;

    abstract class Filter {

        abstract public function apply( Builder $builder, $values = null );

        public static function make(): static {
            return new static();
        }

        /**
         * @param Builder $builder
         *
         * @return array
         */
        public function getFilterables( Builder $builder ): array {
            if ( ! $this->getModel( $builder ) instanceof Filterable ) {
                return [];
            }

            return $builder->getModel()->getFilterableAttributes();
        }

        public function resolveAttribute( array $filterables, string $attribute ): string {
            return is_string( ( $key = array_search( $attribute, $filterables ) ) ) ? $key : $attribute;
        }

        /**
         * @param Builder $builder
         *
         * @return array
         */
        public function getPivotFilterable( Builder $builder ): array {
            // TODO: alias for pivot columns
            if ( ! $this->getModel( $builder ) instanceof Filterable ) {
                return [];
            }
            if ( $builder instanceof Relation and ! ( $builder instanceof HasMany ) ) {
                return $builder->getPivotColumns();
            }

            return [];
        }

        public function getLoadableRelations( Builder $builder ): array {
            if ( ! $this->getModel( $builder ) instanceof Loadable ) {
                return [];
            }

            return array_keys( $this->getModel( $builder )->getLoadableRelations() );
        }

        /**
         * @param Builder $builder
         *
         * @return string
         */
        public function getTable( Builder $builder ): string {
            return $builder->getModel()->getTable();
        }

        /**
         * @param Builder $builder
         *
         * @return Model
         */
        public function getModel( Builder $builder ): Model {
            return $builder->getModel();
        }
    }
