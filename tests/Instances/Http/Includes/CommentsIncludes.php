<?php

	namespace Hans\Tests\Valravn\Instances\Http\Includes;

	use Hans\Tests\Valravn\Core\Resources\Comment\CommentCollection;
	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
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
		 * @return JsonResource
		 */
		public function toResource(): JsonResource {
			return CommentCollection::make( $this->getBuilder()->get() );
		}
	}