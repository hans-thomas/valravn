<?php

	namespace Hans\Tests\Valravn\Instances\Http\Resources;

	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
	use Illuminate\Database\Eloquent\Model;

	class SampleCollection extends BaseResourceCollection {

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