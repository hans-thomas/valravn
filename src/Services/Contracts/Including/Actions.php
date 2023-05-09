<?php

	namespace Hans\Valravn\Services\Contracts\Including;

	use Hans\Valravn\Models\Contracts\Filtering\Filterable;
	use Illuminate\Contracts\Database\Eloquent\Builder;

	abstract class Actions {

		public function __construct( private Builder $builder ) { }

		/**
		 * Implement a custom logic
		 *
		 * @param array $params
		 *
		 * @return void
		 */
		abstract function apply( array $params ): void;

		/**
		 * Return filterable column
		 *
		 * @param string $column
		 *
		 * @return string
		 */
		protected function getFilterableColumn( string $column ): string {
			if ( $this->builder()->getModel() instanceof Filterable ) {
				$filterable = $this->builder()->getModel()->getFilterableAttributes();
				if ( ( $position = array_search( $column, $filterable ) ) !== false ) {
					return is_string( $position ) ? $position : $column;
				}
			}

			return $column;
		}

		/**
		 * Return builder instance
		 *
		 * @return Builder
		 */
		protected function builder(): Builder {
			return $this->builder;
		}

	}
