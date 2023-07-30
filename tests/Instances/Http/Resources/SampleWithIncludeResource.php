<?php

namespace Hans\Valravn\Tests\Instances\Http\Resources;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Hans\Valravn\Tests\Core\Models\Category;
use Hans\Valravn\Tests\Core\Models\Post;
use Illuminate\Database\Eloquent\Model;

class SampleWithIncludeResource extends ValravnJsonResource
{
    public function extract(Model $model): ?array
    {
        return [
            'id'   => $model->id,
            'name' => $model->name,
        ];
    }

    public function type(): string
    {
        return 'samples';
    }

    protected function loaded(&$data): void
    {
        $this->loadedPivots(
            data: $data,
            includes: [
                ( new Post() )->getForeignKey(),
                ( new Category() )->getForeignKey(),
            ],
            alias: [
                ( new Category() )->getForeignKey() => 'category_identifier',
            ]
        );
    }
}
