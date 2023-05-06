<?php

	namespace Hans\Tests\Valravn\Instances\Http\Includes;

	use Hans\Tests\Valravn\Core\Resources\Post\PostCollection;
	use Hans\Valravn\Http\Resources\Contracts\Includes;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Resources\Json\JsonResource;

	class PostsIncludes extends Includes {

		/**
		 * @param Model $model
		 *
		 * @return Builder
		 */
		public function apply( Model $model ): Builder {
			return $model->posts();
		}

		/**
		 * @return JsonResource
		 */
		public function toResource(): JsonResource {
			return PostCollection::make( $this->getBuilder()->get() );
		}
	}