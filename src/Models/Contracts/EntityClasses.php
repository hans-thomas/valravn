<?php

	namespace Hans\Valravn\Models\Contracts;

	use Hans\Valravn\Repositories\Contracts\Repository;

	interface EntityClasses {

		/**
		 * Return related repository class
		 *
		 * @return Repository
		 */
		public function getRepository(): Repository;

		/**
		 * Return related service class
		 *
		 * @return object
		 */
		public function getService(): object;

		/**
		 * Return related relations service class
		 *
		 * @return object
		 */
		public function getRelationsService(): object;
	}
