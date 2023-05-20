<?php

	namespace Hans\Tests\Valravn\Instances\Http\Resources;

	use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
	use Illuminate\Database\Eloquent\Model;

	class SampleWithTypeOverrideResource extends ValravnJsonResource {

		public function extract( Model $model ): ?array {
			return [
				'id'   => $model->id,
				'name' => $model->name,
				'type' => 'leaving heaven',
			];
		}

		public function type(): string {
			return 'samples';
		}

	}