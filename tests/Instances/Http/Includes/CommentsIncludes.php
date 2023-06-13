<?php

	namespace Hans\Valravn\Tests\Instances\Http\Includes;

	use Hans\Valravn\Tests\Core\Resources\Comment\CommentCollection;
	use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
	use Hans\Valravn\Http\Resources\Contracts\Includes;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Resources\Json\JsonResource;

	class CommentsIncludes extends Includes {

		/**
		 * @param Model $model
		 *
		 * @return Builder
		 */
		public function apply( Model $model ): Builder {
			return $model->comments();
		}

		/**
		 * @return ValravnJsonResource
		 */
		public function toResource(): ValravnJsonResource {
			return CommentCollection::make( $this->getBuilder()->get() );
		}
	}