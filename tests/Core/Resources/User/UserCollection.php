<?php

namespace Hans\Valravn\Tests\Core\Resources\User;

use Hans\Valravn\Http\Resources\Contracts\VResourceCollection;
use Illuminate\Database\Eloquent\Model;

class UserCollection extends VResourceCollection
{
    /**
     * @param Model $model
     *
     * @return array|null
     */
    public function extract(Model $model): ?array
    {
        return null;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'users';
    }
}
