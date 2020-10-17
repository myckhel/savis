<?php

namespace App\Policies;

use App\Models\CustomerProperty;
use App\Models\User;
use App\Models\Business;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any customer properties.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the customer property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function view(User $user, CustomerProperty $customerProperty)
    {
        //
    }

    /**
     * Determine whether the user can create customer properties.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the customer property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function update(User $user, CustomerProperty $customerProperty)
    {
    }

    /**
     * Determine whether the user can delete the customer property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function delete(User $user, CustomerProperty $customerProperty)
    {
      return $user->id == $customerProperty->customer->user_id;
    }

    /**
     * Determine whether the user can restore the customer property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function restore(User $user, CustomerProperty $customerProperty)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the customer property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function forceDelete(User $user, CustomerProperty $customerProperty)
    {
        //
    }
}
