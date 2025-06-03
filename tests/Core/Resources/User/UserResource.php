<?php

namespace Hans\Valravn\Tests\Core\Resources\User;

use Hans\Valravn\Http\Resources\VJsonResource;
use Illuminate\Database\Eloquent\Model;

class UserResource extends VJsonResource
{
    /**
     * @param Model $model
     *
     * @return array|null
     */
    public function extract(Model $model): ?array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
        ];
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'users';
    }
}
