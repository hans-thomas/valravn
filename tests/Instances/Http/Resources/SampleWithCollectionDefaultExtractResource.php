<?php

	namespace Hans\Tests\Valravn\Instances\Http\Resources;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Database\Eloquent\Model;

	class SampleWithCollectionDefaultExtractResource extends BaseJsonResource {

		/**
		 * @param Model $model
		 *
		 * @return array|null
		 */
		public function extract( Model $model ): ?array {
			return [
				'id'      => $model->id,
				'name'    => $model->name,
				'extract' => 'not default on resource'
			];
		}

		/**
		 * @return string
		 */
		public function type(): string {
			return 'samples';
		}
	}