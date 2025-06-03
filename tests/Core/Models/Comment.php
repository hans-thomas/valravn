<?php

namespace Hans\Valravn\Tests\Core\Models;

use Hans\Valravn\Models\VModel;
use Hans\Valravn\Tests\Core\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends VModel
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return CommentFactory::new();
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
