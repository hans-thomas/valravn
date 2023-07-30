<?php

namespace Hans\Valravn\Tests\Instances\Http\Queries;

    use Hans\Valravn\Http\Resources\Contracts\ResourceQuery;
    use Hans\Valravn\Tests\Core\Resources\Category\CategoryResource;
    use Illuminate\Database\Eloquent\Model;

    class FirstCategoryQuery extends ResourceQuery
    {
        /**
         * @param Model $model
         *
         * @return array
         */
        public function apply(Model $model): array
        {
            return [
                'first_category' => CategoryResource::make($model->categories()->first()),
            ];
        }
    }
