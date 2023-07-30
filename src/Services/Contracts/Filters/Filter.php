<?php

namespace Hans\Valravn\Services\Contracts\Filters;

    use Hans\Valravn\Models\Contracts\Filterable;
    use Hans\Valravn\Models\Contracts\Loadable;
    use Illuminate\Contracts\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\Relation;

    abstract class Filter
    {
        /**
         * Implement a custom logic.
         *
         * @param Builder $builder
         * @param         $values
         *
         * @return mixed
         */
        abstract public function apply(Builder $builder, $values = null);

        /**
         * Create an instance in static way.
         *
         * @return static
         */
        public static function make(): static
        {
            return new static();
        }

        /**
         * Return filterable attributes.
         *
         * @param Builder $builder
         *
         * @return array
         */
        public function getFilterables(Builder $builder): array
        {
            if (!$this->getModel($builder) instanceof Filterable) {
                return [];
            }

            return $builder->getModel()->getFilterableAttributes();
        }

        /**
         * Return attribute's alias if exists, otherwise return attribute.
         *
         * @param array  $filterables
         * @param string $attribute
         *
         * @return string
         */
        public function resolveAttribute(array $filterables, string $attribute): string
        {
            return is_string($key = array_search($attribute, $filterables)) ? $key : $attribute;
        }

        /**
         * Return filterable pivot columns.
         *
         * @param Builder $builder
         *
         * @return array
         */
        public function getPivotFilterable(Builder $builder): array
        {
            // TODO: alias for pivot columns
            if (!$this->getModel($builder) instanceof Filterable) {
                return [];
            }
            if ($builder instanceof Relation and !($builder instanceof HasMany)) {
                return $builder->getPivotColumns();
            }

            return [];
        }

        /**
         * Return Loadable relations.
         *
         * @param Builder $builder
         *
         * @return array
         */
        public function getLoadableRelations(Builder $builder): array
        {
            if (!$this->getModel($builder) instanceof Loadable) {
                return [];
            }

            return array_keys($this->getModel($builder)->getLoadableRelations());
        }

        /**
         * Return table name of the given builder.
         *
         * @param Builder $builder
         *
         * @return string
         */
        public function getTable(Builder $builder): string
        {
            return $builder->getModel()->getTable();
        }

        /**
         * Return related model instance of the given builder.
         *
         * @param Builder $builder
         *
         * @return Model
         */
        public function getModel(Builder $builder): Model
        {
            return $builder->getModel();
        }
    }
