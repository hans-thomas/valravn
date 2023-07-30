<?php

namespace Hans\Valravn\Policies\Contracts;

use Hans\Valravn\Policies\Traits\PolicyHelperTrait;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class ValravnPolicy
{
    use HandlesAuthorization;
    use PolicyHelperTrait;

    /**
     * Set the related model class.
     *
     * @return string
     */
    abstract protected function getModel(): string;

    /**
     * Determine whether the user can view any models.
     *
     * @param Authenticatable $user
     *
     * @return bool
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param Authenticatable $user
     * @param Model           $model
     *
     * @return bool
     */
    public function view(Authenticatable $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can create models.
     *
     * @param Authenticatable $user
     *
     * @return bool
     */
    public function create(Authenticatable $user): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param Authenticatable $user
     * @param Model           $model
     *
     * @return bool
     */
    public function update(Authenticatable $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can batch update the model.
     *
     * @param Authenticatable $user
     * @param Collection      $data
     *
     * @return bool
     */
    public function batchUpdate(Authenticatable $user, Collection $data): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param Authenticatable $user
     * @param Model           $model
     *
     * @return bool
     */
    public function delete(Authenticatable $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param Authenticatable $user
     * @param Model           $model
     *
     * @return bool
     */
    public function restore(Authenticatable $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param Authenticatable $user
     * @param Model           $model
     *
     * @return bool
     */
    public function forceDelete(Authenticatable $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }
}
