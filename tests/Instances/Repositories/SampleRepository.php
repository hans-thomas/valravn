<?php

namespace Hans\Valravn\Tests\Instances\Repositories;

    use Hans\Valravn\Repositories\Contracts\Repository;
    use Hans\Valravn\Tests\Core\Models\Post;
    use Illuminate\Contracts\Database\Eloquent\Builder;

    class SampleRepository extends Repository
    {
        /**
         * @return Builder
         */
        protected function getQueryBuilder(): Builder
        {
            return Post::query();
        }
    }
