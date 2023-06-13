<?php

	namespace Hans\Valravn\Tests\Instances\Http\Resources;

	use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class SampleCollection extends ValravnResourceCollection {

		/**
		 * @param Model $model
		 *
		 * @return array|null
		 */
		public function extract( Model $model ): ?array {
			return [
				'id'   => $model->id,
			];
		}

		/**
		 * @return string
		 */
		public function type(): string {
			return 'samples';
		}
	}