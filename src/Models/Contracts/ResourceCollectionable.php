<?php

	namespace Hans\Valravn\Models\Contracts;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;

	interface ResourceCollectionable {

		/**
		 * Return related resource class
		 *
		 * @return BaseJsonResource
		 */
		public static function getResource(): BaseJsonResource;

		/**
		 * Convert current instance to a related resource class
		 *
		 * @return BaseJsonResource
		 */
		public function toResource(): BaseJsonResource;

		/**
		 * Return related resource collection class
		 *
		 * @return BaseResourceCollection
		 */
		public static function getResourceCollection(): BaseResourceCollection;
	}
