<?php

	namespace Hans\Valravn\Http\Resources\Contracts;


	abstract class CollectionQuery {

		/**
		 * Store processed data
		 *
		 * @var array
		 */
		private array $data = [];

		/**
		 * Implement a custom logic
		 *
		 * @param BaseJsonResource $resource
		 *
		 * @return array
		 */
		abstract public function apply( BaseJsonResource $resource ): array;

		/**
		 * Create an instance in static way
		 *
		 * @return static
		 */
		public static function make(): static {
			return new static();
		}

		/**
		 * Apply the custom logic and store processed data
		 *
		 * @param BaseJsonResource $resource
		 *
		 * @return $this
		 */
		public function run( BaseJsonResource $resource ): self {
			$this->data = $this->apply( $resource );

			return $this;
		}

		/**
		 * Merge processed data to a resource class
		 *
		 * @param BaseJsonResource $resource
		 *
		 * @return void
		 */
		public function mergeDataInto( BaseJsonResource $resource ): void {
			$resource->addAdditional( $this->getData() );
		}

		/**
		 * Return processed data
		 *
		 * @return array
		 */
		public function getData(): array {
			return $this->data;
		}
	}
