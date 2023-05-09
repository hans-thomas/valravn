<?php

	namespace Hans\Valravn\Models\Contracts\Filtering;

	interface Loadable {

		/**
		 * List of relationships that can be loaded
		 *
		 * @return array
		 */
		public function getLoadableRelations(): array;
	}
