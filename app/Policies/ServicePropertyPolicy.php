<?php

namespace App\Policies;

use App\ServiceProperty;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any service properties.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view any service properties.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function attach(User $user, ServiceProperty $serviceProperty)
    {
      return $user->id === $serviceProperty->service->user_id;
    }

    /**
     * Determine whether the user can view the service property.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProperty  $serviceProperty
     * @return mixed
     */
    public function view(User $user, ServiceProperty $serviceProperty)
    {
        //
    }

    /**
     * Determine whether the user can create service properties.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the service property.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProperty  $serviceProperty
     * @return mixed
     */
    public function update(User $user, ServiceProperty $serviceProperty)
    {
        //
    }

    /**
     * Determine whether the user can delete the service property.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProperty  $serviceProperty
     * @return mixed
     */
    public function delete(User $user, ServiceProperty $serviceProperty)
    {
        //
    }

    /**
     * Determine whether the user can restore the service property.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProperty  $serviceProperty
     * @return mixed
     */
    public function restore(User $user, ServiceProperty $serviceProperty)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the service property.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProperty  $serviceProperty
     * @return mixed
     */
    public function forceDelete(User $user, ServiceProperty $serviceProperty)
    {
        //
    }
}
