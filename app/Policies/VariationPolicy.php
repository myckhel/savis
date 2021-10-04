<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Variation;
use Illuminate\Auth\Access\HandlesAuthorization;

class VariationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any variations.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Variation  $variation
     * @return mixed
     */
    public function view(User $user, Variation $variation)
    {
      return !!$user->hasVariation($variation->id);
    }

    /**
     * Determine whether the user can create variations.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Variation  $variation
     * @return mixed
     */
    public function update(User $user, Variation $variation)
    {
      return !!$user->hasVariation($variation->id);
    }

    /**
     * Determine whether the user can delete the variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Variation  $variation
     * @return mixed
     */
    public function delete(User $user, Variation $variation)
    {
      return !!$user->hasVariation($variation->id);
    }

    /**
     * Determine whether the user can restore the variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Variation  $variation
     * @return mixed
     */
    public function restore(User $user, Variation $variation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the variation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Variation  $variation
     * @return mixed
     */
    public function forceDelete(User $user, Variation $variation)
    {
        //
    }
}
