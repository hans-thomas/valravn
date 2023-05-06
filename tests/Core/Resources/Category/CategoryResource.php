<?php

	namespace Hans\Tests\Valravn\Core\Resources\Category;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Database\Eloquent\Model;

	class CategoryResource extends BaseJsonResource {

		/**
		 * @param Model $model
		 *
		 * @return array|null
		 */
		public function extract( Model $model ): ?array {
			return [
				'id'   => $model->id,
				'name' => $model->name
			];
		}

		/**
		 * @return string
		 */
		public function type(): string {
			return 'categories';
		}
	}