<?php

namespace Hans\Valravn\Tests\Instances\Http\Requests;

use Hans\Valravn\Http\Requests\Contracts\Relations\MorphedByManyRequest;
use Hans\Valravn\Tests\Core\Models\Post;

class PostCategoriesMorphedByManyRequest extends MorphedByManyRequest
{
    /**
     * Get related model class.
     *
     * @return string
     */
    protected function model(): string
    {
        return Post::class;
    }
}
