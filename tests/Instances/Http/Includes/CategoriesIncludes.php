<?php

	namespace Hans\Tests\Valravn\Instances\Http\Includes;

	use Hans\Tests\Valravn\Core\Resources\Category\CategoryCollection;
	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\Includes;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Resources\Json\JsonResource;

	class CategoriesIncludes extends Includes {

		/**
		 * @param Model $model
		 *
		 * @return Builder
		 */
		public function apply( Model $model ): Builder {
			return $model->categories();
		}

		/**
		 * @return BaseJsonResource
		 */
		public function toResource(): BaseJsonResource {
			return CategoryCollection::make( $this->getBuilder()->get() );
		}
	}