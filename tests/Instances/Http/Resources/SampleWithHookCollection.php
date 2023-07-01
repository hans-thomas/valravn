<?php

	namespace Hans\Valravn\Tests\Instances\Http\Resources;

	use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Collection;

	class SampleWithHookCollection extends ValravnResourceCollection {

		/**
		 * @param Model $model
		 *
		 * @return array|null
		 */
		public function extract( Model $model ): ?array {
			return [
				'id' => $model->id,
			];
		}

		/**
		 * @return string
		 */
		public function type(): string {
			return 'samples';
		}

		/**
		 * @param Collection $response
		 *
		 * @return void
		 */
		protected function allLoaded( Collection &$response ): void {
			$this->addAdditional( [
				'all-loaded' => 'will you still love me when i no longer young and beautiful?'
			] );
		}


	}