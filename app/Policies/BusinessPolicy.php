<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\Business;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessPolicy
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
     * @param  \App\Models\Business  $business
     * @return mixed
     */
    public function view(User $user, Business $business)
    {
      return true;
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
     * @param  \App\Models\Business  $business
     * @return mixed
     */
    public function update(User $user, Business $business)
    {
      return $user->id == $business->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Business  $business
     * @return mixed
     */
    public function delete(User $user, Business $business)
    {
      return $user->id == $business->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Business  $business
     * @return mixed
     */
    public function restore(User $user, Business $business)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Business  $business
     * @return mixed
     */
    public function forceDelete(User $user, Business $business)
    {
        //
    }

    public function canWork(User $user, Business $business, Service $service = null)
    {
      return !!$business->findWorker($user->id)
        && $service ? $service->business_id === $business->id : true;
    }
    public function work(User $user, Business $business, Service $service = null)
    {
      return !!$business->findWorker($user->id)
        && $service ? $service->business_id === $business->id : true;
    }
}
