<?php

namespace Hans\Valravn\Tests\Instances\Http\Requests;

use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToManyRequest;
use Hans\Valravn\Tests\Core\Models\Post;

class PostCategoriesMorphToManyRequest extends MorphToManyRequest
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
