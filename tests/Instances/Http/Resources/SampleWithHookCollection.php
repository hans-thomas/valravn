<?php

	namespace Hans\Tests\Valravn\Instances\Http\Resources;

	use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
	use Illuminate\Database\Eloquent\Model;

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
		 * @return void
		 */
		protected function allLoaded(): void {
			$this->addAdditional( [
				'all-loaded' => 'will you still love me when i no longer young and beautiful?'
			] );
		}


	}