<?php

	namespace Hans\Tests\Valravn\Core\Models;

	use Hans\Tests\Valravn\Core\Factories\CategoryFactory;
	use Hans\Valravn\Models\ValravnModel;
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;

	class Category extends ValravnModel {
		use HasFactory;

		protected $fillable = [
			'name'
		];

		public function posts(): BelongsToMany {
			return $this->belongsToMany( Post::class );
		}

		/**
		 * Create a new factory instance for the model.
		 *
		 * @return Factory<static>
		 */
		protected static function newFactory() {
			return CategoryFactory::new();
		}

	}