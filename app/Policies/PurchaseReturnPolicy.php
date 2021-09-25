<?php

namespace App\Policies;

use App\PurchaseReturn;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseReturnPolicy
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
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return mixed
     */
    public function view(User $user, PurchaseReturn $purchaseReturn)
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
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return mixed
     */
    public function update(User $user, PurchaseReturn $purchaseReturn)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return mixed
     */
    public function delete(User $user, PurchaseReturn $purchaseReturn)
    {
        if( $purchaseReturn->status == 'Draft' && $user->can('delete_purchase')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return mixed
     */
    public function restore(User $user, PurchaseReturn $purchaseReturn)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return mixed
     */
    public function forceDelete(User $user, PurchaseReturn $purchaseReturn)
    {
        //
    }
}
