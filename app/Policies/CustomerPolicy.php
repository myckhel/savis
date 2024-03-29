<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
      return false;
    }

    /**
     * Determine whether the user can view any customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAnyCustomer(Customer $user)
    {
      return false;
    }

    /**
     * Determine whether the user can view the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function view(User $user, Customer $customer)
    {
      return true;
      // return $customer->business->users()->whereUserId($user->id)->first();
    }

    /**
     * Determine whether the user can create customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function update($user, Customer $customer)
    {
      if ($user->id == $customer->id) {
        return true;
      // } elseif($user->isAdmin() && $user->customers()) {
      }

      return false;
    }

    /**
     * Determine whether the user can delete the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function delete(User $user, Customer $customer)
    {
      return $user->id == $customer->user_id || $user->id == $customer->business->owner->id;
    }

    /**
     * Determine whether the user can restore the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function restore(User $user, Customer $customer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function forceDelete(User $user, Customer $customer)
    {
        //
    }

    public function remove(User $user, Customer $customer){
      return in_array($user->id, $customer->clients()->pluck('user_id')->toArray());
    }

    public function add(User $user, Customer $customer){
      return !in_array($user->id, $customer->clients()->pluck('user_id')->toArray());
    }
}
