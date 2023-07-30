<?php

namespace Hans\Valravn\Tests\Instances\Http\Requests;

use Hans\Valravn\Http\Requests\Contracts\Relations\HasManyRequest;
use Hans\Valravn\Tests\Core\Models\Post;

class PostCategoriesHasManyRequest extends HasManyRequest
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
