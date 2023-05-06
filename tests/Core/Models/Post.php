<?php

	namespace Hans\Tests\Valravn\Core\Models;

	use Hans\Tests\Valravn\Core\Factories\PostFactory;
	use Hans\Valravn\Models\BaseModel;
	use Hans\Valravn\Models\Contracts\Filtering\Filterable;
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	use Illuminate\Database\Eloquent\Relations\HasMany;

	class Post extends BaseModel implements Filterable {
		use HasFactory;

		protected $fillable = [
			'title',
			'content',
		];

		public function comments(): HasMany {
			return $this->hasMany( Comment::class );
		}

		public function categories(): BelongsToMany {
			return $this->belongsToMany( Category::class );
		}

		/**
		 * Create a new factory instance for the model.
		 *
		 * @return Factory<static>
		 */
		protected static function newFactory() {
			return PostFactory::new();
		}

		/**
		 * @return array
		 */
		public function getFilterableAttributes(): array {
			return [
				'id',
				'title',
				'content',
			];
		}
	}