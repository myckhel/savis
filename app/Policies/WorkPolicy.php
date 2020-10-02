<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use App\Models\work;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any works.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the work.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\work  $work
     * @return mixed
     */
    public function view($user, work $work)
    {
      $job = $user->jobs()->find($work->id);
      return $job->id ?? false == $work->id;
    }

    /**
     * Determine whether the user can create works.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the work.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\work  $work
     * @return mixed
     */
    public function update(User $user, work $work)
    {
      return $user->jobs()->find($work->id)->id == $work->id;
    }

    /**
     * Determine whether the user can delete the work.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\work  $work
     * @return mixed
     */
    public function delete(User $user, work $work)
    {
        //
    }

    /**
     * Determine whether the user can restore the work.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\work  $work
     * @return mixed
     */
    public function restore(User $user, work $work)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the work.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\work  $work
     * @return mixed
     */
    public function forceDelete(User $user, work $work)
    {
        //
    }
}
