<?php

namespace App\Policies;

use App\CustomerProperty;
use App\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any customer properties.
     *
     * @param  \App\Customer  $user
     * @return mixed
     */
    public function viewAny(Customer $user)
    {
        //
    }

    /**
     * Determine whether the user can view the customer property.
     *
     * @param  \App\Customer  $user
     * @param  \App\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function view(Customer $user, CustomerProperty $customerProperty)
    {
        //
    }

    /**
     * Determine whether the user can create customer properties.
     *
     * @param  \App\Customer  $user
     * @return mixed
     */
    public function create(Customer $user)
    {
        //
    }

    /**
     * Determine whether the user can update the customer property.
     *
     * @param  \App\Customer  $user
     * @param  \App\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function update(Customer $user, CustomerProperty $customerProperty)
    {
      return $user->id == $customerProperty->customer_id;
    }

    /**
     * Determine whether the user can delete the customer property.
     *
     * @param  \App\Customer  $user
     * @param  \App\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function delete(Customer $user, CustomerProperty $customerProperty)
    {
      return $user->id == $customerProperty->customer_id;
    }

    /**
     * Determine whether the user can restore the customer property.
     *
     * @param  \App\Customer  $user
     * @param  \App\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function restore(Customer $user, CustomerProperty $customerProperty)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the customer property.
     *
     * @param  \App\Customer  $user
     * @param  \App\CustomerProperty  $customerProperty
     * @return mixed
     */
    public function forceDelete(Customer $user, CustomerProperty $customerProperty)
    {
        //
    }
}
