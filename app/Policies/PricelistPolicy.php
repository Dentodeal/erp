<?php

namespace App\Policies;

use App\Pricelist;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricelistPolicy
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
     * @param  \App\Pricelist  $pricelist
     * @return mixed
     */
    public function view(User $user, Pricelist $pricelist)
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
     * @param  \App\Pricelist  $pricelist
     * @return mixed
     */
    public function update(User $user, Pricelist $pricelist)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pricelist  $pricelist
     * @return mixed
     */
    public function delete(User $user, Pricelist $pricelist)
    {
        if($pricelist->products()->count() > 0){
            return Response::deny('There are records registered for this pricelist. Cannot delete!!');
        }
        if($pricelist->is_system){
            return false;
        }
        if($user->hasRole('Super Admin'))
        {
            return true;
        }
        if($user->can('delete_pricelist'))
        {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pricelist  $pricelist
     * @return mixed
     */
    public function restore(User $user, Pricelist $pricelist)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pricelist  $pricelist
     * @return mixed
     */
    public function forceDelete(User $user, Pricelist $pricelist)
    {
        //
    }
}
