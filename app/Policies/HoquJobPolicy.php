<?php

namespace App\Policies;

use App\Models\HoquJob;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HoquJobPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HoquJob  $hoquJob
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, HoquJob $hoquJob)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->email === "team@webmapp.it";
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HoquJob  $hoquJob
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, HoquJob $hoquJob)
    {
        return $user->email === "team@webmapp.it";
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HoquJob  $hoquJob
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, HoquJob $hoquJob)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HoquJob  $hoquJob
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, HoquJob $hoquJob)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HoquJob  $hoquJob
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, HoquJob $hoquJob)
    {
        //
    }
}
