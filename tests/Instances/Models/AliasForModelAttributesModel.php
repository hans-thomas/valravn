<?php

	namespace Hans\Tests\Valravn\Instances\Models;

	use Hans\Tests\Valravn\Core\Models\Comment;
	use Hans\Tests\Valravn\Core\Models\Post;
	use Illuminate\Database\Eloquent\Casts\Attribute;

	class AliasForModelAttributesModel extends Comment {

		protected $table = 'comments';
		protected $fillable = [
			'content',
			'its_post_id'
		];

		public function itsPostId(): Attribute {
			return new Attribute( get: fn() => $this->{Post::foreignKey()} );
		}

		/**
		 * @return void
		 */
		protected static function booted() {
			self::saving(
				fn( self $model ) => self::aliasForModelAttributes(
					$model,
					[
						Post::foreignKey() => 'its_post_id'
					]
				)
			);
		}


	}