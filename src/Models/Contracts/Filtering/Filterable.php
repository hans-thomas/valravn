<?php

	namespace Hans\Valravn\Models\Contracts\Filtering;

	interface Filterable {

		/**
		 * List of attributes that can be filtered
		 *
		 * @return array
		 */
		public function getFilterableAttributes(): array;
	}
