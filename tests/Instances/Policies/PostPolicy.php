<?php

namespace Hans\Valravn\Tests\Instances\Policies;

use Hans\Valravn\Policies\Contracts\VPolicy;
use Hans\Valravn\Tests\Core\Models\Post;

class PostPolicy extends VPolicy
{
    /** @inheritDoc */
    protected function getModel(): string
    {
        return Post::class;
    }
}
