<?php

namespace Hans\Valravn\Tests\Core\Resources\User;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Illuminate\Database\Eloquent\Model;

class UserResource extends ValravnJsonResource
{
    /**
     * @param Model $model
     *
     * @return array|null
     */
    public function extract(Model $model): ?array
    {
        return [
            'id'    => $model->id,
            'name'  => $model->name,
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
