<?php

namespace Hans\Valravn\Tests\Core\Models;

    use Hans\Valravn\Models\Contracts\Filterable;
    use Hans\Valravn\Models\Contracts\Loadable;
    use Hans\Valravn\Models\ValravnModel;
    use Hans\Valravn\Tests\Core\Factories\PostFactory;
    use Hans\Valravn\Tests\Core\Resources\Comment\CommentCollection;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Post extends ValravnModel implements Filterable, Loadable
    {
        use HasFactory;

        protected $fillable = [
            'title',
            'content',
        ];

        public function comments(): HasMany
        {
            return $this->hasMany(Comment::class);
        }

        public function categories(): BelongsToMany
        {
            return $this->belongsToMany(Category::class)
                        ->withPivot('order');
        }

        /**
         * Create a new factory instance for the model.
         *
         * @return Factory<static>
         */
        protected static function newFactory()
        {
            return PostFactory::new();
        }

        /**
         * @return array
         */
        public function getFilterableAttributes(): array
        {
            return [
                'id',
                'title',
                'content',
            ];
        }

        /**
         * List of relationships that can be loaded.
         *
         * @return array
         */
        public function getLoadableRelations(): array
        {
            return [
                'comments' => CommentCollection::class,
            ];
        }
    }
