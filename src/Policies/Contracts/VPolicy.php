<?php

namespace Hans\Valravn\Policies\Contracts;

use Hans\Valravn\Policies\Traits\PolicyHelperTrait;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;

abstract class VPolicy
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
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Model           $model
     *
     * @return bool
     */
    public function view(User $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Model           $model
     *
     * @return bool
     */
    public function update(User $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can batch update the model.
     *
     * @param User $user
     * @param Collection      $data
     *
     * @return bool
     */
    public function batchUpdate(User $user, Collection $data): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Model           $model
     *
     * @return bool
     */
    public function delete(User $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Model           $model
     *
     * @return bool
     */
    public function restore(User $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Model           $model
     *
     * @return bool
     */
    public function forceDelete(User $user, Model $model): bool
    {
        return $user->can($this->guessAbility());
    }
}
