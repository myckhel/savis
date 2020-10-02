<?php

namespace App\Policies;

use App\Models\ServiceVariation;
use App\Models\Service;
use App\Models\Variation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceVariationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any service variations.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the service variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceVariation  $serviceVariation
     * @return mixed
     */
    public function view(User $user, ServiceVariation $serviceVariation)
    {
      return $user->id === $serviceVariation->service->user->id;
    }

    /**
     * Determine whether the user can create service variations.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the service variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceVariation  $serviceVariation
     * @return mixed
     */
    public function update(User $user, ServiceVariation $serviceVariation)
    {
      return $user->id === $serviceVariation->service->user->id;
    }

    /**
     * Determine whether the user can delete the service variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceVariation  $serviceVariation
     * @return mixed
     */
    public function delete(User $user, ServiceVariation $serviceVariation)
    {
      return $user->id === $serviceVariation->service->user->id;
    }

    /**
     * Determine whether the user can restore the service variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceVariation  $serviceVariation
     * @return mixed
     */
    public function restore(User $user, ServiceVariation $serviceVariation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the service variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceVariation  $serviceVariation
     * @return mixed
     */
    public function forceDelete(User $user, ServiceVariation $serviceVariation)
    {
        //
    }
}
