<?php

namespace App\Policies;

use App\User;
use App\Variation;
use Illuminate\Auth\Access\HandlesAuthorization;

class VariationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any variations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the variation.
     *
     * @param  \App\User  $user
     * @param  \App\Variation  $variation
     * @return mixed
     */
    public function view(User $user, Variation $variation)
    {
      return $user->id == $variation->user_id;
    }

    /**
     * Determine whether the user can create variations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the variation.
     *
     * @param  \App\User  $user
     * @param  \App\Variation  $variation
     * @return mixed
     */
    public function update(User $user, Variation $variation)
    {
      return $user->id == $variation->user_id;
    }

    /**
     * Determine whether the user can delete the variation.
     *
     * @param  \App\User  $user
     * @param  \App\Variation  $variation
     * @return mixed
     */
    public function delete(User $user, Variation $variation)
    {
      return $user->id == $variation->user_id;
    }

    /**
     * Determine whether the user can restore the variation.
     *
     * @param  \App\User  $user
     * @param  \App\Variation  $variation
     * @return mixed
     */
    public function restore(User $user, Variation $variation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the variation.
     *
     * @param  \App\User  $user
     * @param  \App\Variation  $variation
     * @return mixed
     */
    public function forceDelete(User $user, Variation $variation)
    {
        //
    }
}
