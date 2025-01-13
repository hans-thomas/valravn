<?php

namespace Hans\Valravn\Tests\Instances\Policies;

use Hans\Valravn\Policies\Contracts\VPolicy;
use Hans\Valravn\Tests\Core\Models\Post;
use Illuminate\Foundation\Auth\User;

class PostPolicy extends VPolicy
{
    /** @inheritDoc */
    protected function getModel(): string
    {
        return Post::class;
    }
}