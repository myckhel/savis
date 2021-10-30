<?php

namespace App\Policies;

use App\Models\Support;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Support  $support
     * @return mixed
     */
    public function view(User $user, Support $support)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Support  $support
     * @return mixed
     */
    public function update(User $user, Support $support)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Support  $support
     * @return mixed
     */
    public function delete(User $user, Support $support)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Support  $support
     * @return mixed
     */
    public function restore(User $user, Support $support)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Support  $support
     * @return mixed
     */
    public function forceDelete(User $user, Support $support)
    {
        //
    }

    /**
     * Determine whether the user can close the support ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Support  $support
     * @return mixed
     */
    public function close(User $user, Support $support)
    {
      return !$support->closed_at && ($user->id === $support->user_id || $user->relatedToConversation($support->conversation));
    }

    /**
     * Determine whether the user can join the support conversation.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Support  $support
     * @return mixed
     */
    public function join(User $user, Support $support)
    {
      return $user->can('support') && !$user->relatedToConversation($support->conversation);
    }
}
