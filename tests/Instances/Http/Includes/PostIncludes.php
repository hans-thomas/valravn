<?php

	namespace Hans\Tests\Valravn\Instances\Http\Includes;

	use Hans\Tests\Valravn\Core\Resources\Post\PostResource;
	use Hans\Valravn\Http\Resources\Contracts\Includes;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Resources\Json\JsonResource;

	class PostIncludes extends Includes {

		/**
		 * @param Model $model
		 *
		 * @return Builder
		 */
		public function apply( Model $model ): Builder {
			return $model->post();
		}

		/**
		 * @return JsonResource
		 */
		public function toResource(): JsonResource {
			return PostResource::make( $this->getBuilder()->first() );
		}
	}