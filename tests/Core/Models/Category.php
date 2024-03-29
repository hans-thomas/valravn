<?php

namespace Hans\Valravn\Tests\Core\Models;

use Hans\Valravn\Models\Contracts\Filterable;
use Hans\Valravn\Models\ValravnModel;
use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends ValravnModel implements Filterable
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
                    ->withPivot('order');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return CategoryFactory::new();
    }

    /**
     * List of attributes that can be filtered.
     *
     * @return array
     */
    public function getFilterableAttributes(): array
    {
        return [
            'id',
            'name',
        ];
    }
}
