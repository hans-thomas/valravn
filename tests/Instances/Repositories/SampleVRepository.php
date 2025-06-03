<?php

namespace Hans\Valravn\Tests\Instances\Repositories;

use Hans\Valravn\Repositories\Contracts\VRepository;
use Hans\Valravn\Tests\Core\Models\Post;
use Illuminate\Contracts\Database\Eloquent\Builder;

class SampleVRepository extends VRepository
{
    /**
     * @return Builder
     */
    protected function getQueryBuilder(): Builder
    {
        return Post::query();
    }
}
