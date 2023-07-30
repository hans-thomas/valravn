<?php

namespace Hans\Valravn\Tests\Core\Models;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
use Hans\Valravn\Models\Contracts\ResourceCollectionable;
use Hans\Valravn\Tests\Core\Factories\UserFactory;
use Hans\Valravn\Tests\Core\Resources\User\UserCollection;
use Hans\Valravn\Tests\Core\Resources\User\UserResource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements ResourceCollectionable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Return related resource class.
     *
     * @return ValravnJsonResource
     */
    public static function getResource(): ValravnJsonResource
    {
        return UserResource::make(...func_get_args());
    }

    /**
     * Convert current instance to a related resource class.
     *
     * @return ValravnJsonResource
     */
    public function toResource(): ValravnJsonResource
    {
        return self::getResource($this, ...func_get_args());
    }

    /**
     * Return related resource collection class.
     *
     * @return ValravnResourceCollection
     */
    public static function getResourceCollection(): ValravnResourceCollection
    {
        return UserCollection::make(...func_get_args());
    }
}
