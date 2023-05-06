<?php

	namespace Hans\Tests\Valravn\Instances\Http\Queries;

	use Hans\Tests\Valravn\Core\Resources\Category\CategoryResource;
	use Hans\Valravn\Http\Resources\Contracts\ResourceQuery;
	use Illuminate\Database\Eloquent\Model;

	class FirstCategoryQuery extends ResourceQuery {

		/**
		 * @param Model $model
		 *
		 * @return array
		 */
		public function apply( Model $model ): array {
			return [
				'first_category' => CategoryResource::make( $model->categories()->first() )
			];
		}
	}