<?php

	namespace Hans\Tests\Valravn\Instances\Http\Resources;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Database\Eloquent\Model;

	class SampleWithHookResource extends BaseJsonResource {

		public function extract( Model $model ): ?array {
			return [
				'id'   => $model->id,
				'name' => $model->name,
			];
		}

		public function type(): string {
			return 'samples';
		}

		protected function loaded( &$data ) {
			$data[ 'sober' ] = "i might regret this when tomorrow comes";
		}


	}