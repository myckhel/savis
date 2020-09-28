<?php

namespace App\Policies;

use App\CustomerService;
use App\ServiceVariation;
use App\CustomerServiceVariation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerServiceVariationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any customer service variations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the customer service variation.
     *
     * @param  \App\User  $user
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return mixed
     */
    public function view(User $user, CustomerServiceVariation $customerServiceVariation)
    {
        //
    }

    /**
     * Determine whether the user can create customer service variations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, CustomerService $customerService, ServiceVariation $serviceVariation)
    {
    }

    /**
     * Determine whether the user can update the customer service variation.
     *
     * @param  \App\User  $user
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return mixed
     */
    public function update(User $user, CustomerServiceVariation $customerServiceVariation)
    {
        //
    }

    /**
     * Determine whether the user can delete the customer service variation.
     *
     * @param  \App\User  $user
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return mixed
     */
    public function delete(User $user, CustomerServiceVariation $customerServiceVariation)
    {
        //
    }

    /**
     * Determine whether the user can restore the customer service variation.
     *
     * @param  \App\User  $user
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return mixed
     */
    public function restore(User $user, CustomerServiceVariation $customerServiceVariation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the customer service variation.
     *
     * @param  \App\User  $user
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return mixed
     */
    public function forceDelete(User $user, CustomerServiceVariation $customerServiceVariation)
    {
        //
    }
}
