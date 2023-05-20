<?php

	namespace Hans\Valravn\Models\Contracts;

	use Hans\Valravn\Repositories\Contracts\Repository;
	use Hans\Valravn\Services\Contracts\Service;

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
		 * @return Service
		 */
		public function getService(): Service;

		/**
		 * Return related relations service class
		 *
		 * @return Service
		 */
		public function getRelationsService(): Service;
	}
