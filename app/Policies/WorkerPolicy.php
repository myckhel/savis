<?php

namespace App\Policies;

use App\Models\Worker;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worker  $businessUser
     * @return mixed
     */
    public function view(User $user, Worker $businessUser)
    {
      return $businessUser->business->findWorker($user->id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worker  $businessUser
     * @return mixed
     */
    public function update(User $user, Worker $businessUser)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worker  $businessUser
     * @return mixed
     */
    public function delete(User $user, Worker $businessUser)
    {
      return $user->id == $businessUser->user_id || $user->id == $businessUser->business->owner->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worker  $businessUser
     * @return mixed
     */
    public function restore(User $user, Worker $businessUser)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worker  $businessUser
     * @return mixed
     */
    public function forceDelete(User $user, Worker $businessUser)
    {
        //
    }
}
