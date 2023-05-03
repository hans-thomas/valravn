<?php

	namespace Hans\Tests\Valravn\Instances\Http\Resources;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Database\Eloquent\Model;

	class SampleResourceWithDefaultExtract extends BaseJsonResource {

		public function extract( Model $model ): ?array {
			return null;
		}

		public function type(): string {
			return 'samples';
		}

	}