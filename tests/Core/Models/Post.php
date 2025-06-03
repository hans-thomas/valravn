<?php

namespace Hans\Valravn\Tests\Core\Models;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Hans\Valravn\Http\Resources\Contracts\VResourceCollection;
use Hans\Valravn\Models\Contracts\Filterable;
use Hans\Valravn\Models\Contracts\Loadable;
use Hans\Valravn\Models\Contracts\ResourceCollectionable;
use Hans\Valravn\Models\Traits\Paginatable;
use Hans\Valravn\Models\VModel;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Resources\Category\CategoryCollection;
use Hans\Valravn\Tests\Core\Resources\Comment\CommentCollection;
use Hans\Valravn\Tests\Core\Resources\Post\PostCollection;
use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends VModel implements Filterable, Loadable, ResourceCollectionable
{
    use HasFactory;
    use Paginatable;

    protected $fillable = [
        'title',
        'content',
    ];

    /**
     * @inheritDoc
     */
    public static function getVCollection(): VResourceCollection
    {
        return PostCollection::make(...func_get_args());
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
            'comments'   => CommentCollection::class,
            'categories' => CategoryCollection::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public function toVResource(): VJsonResource
    {
        return self::getVResource($this);
    }

    /**
     * @inheritDoc
     */
    public static function getVResource(): VJsonResource
    {
        return PostResource::make(...func_get_args());
    }
}
