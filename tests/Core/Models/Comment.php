<?php

	namespace Hans\Valravn\Tests\Core\Models;

	use Hans\Valravn\Tests\Core\Factories\CommentFactory;
	use Hans\Valravn\Models\ValravnModel;
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;

	class Comment extends ValravnModel {
		use HasFactory;

		protected $fillable = [
			'content',
		];

		public function post(): BelongsTo {
			return $this->belongsTo( Post::class );
		}

		/**
		 * Create a new factory instance for the model.
		 *
		 * @return Factory<static>
		 */
		protected static function newFactory() {
			return CommentFactory::new();
		}

	}