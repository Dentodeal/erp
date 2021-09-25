<?php

namespace App\Policies;

use App\SaleReturn;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaleReturnPolicy
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
     * @param  \App\SaleReturn  $saleReturn
     * @return mixed
     */
    public function view(User $user, SaleReturn $saleReturn)
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
     * @param  \App\SaleReturn  $saleReturn
     * @return mixed
     */
    public function update(User $user, SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SaleReturn  $saleReturn
     * @return mixed
     */
    public function delete(User $user, SaleReturn $saleReturn)
    {
        if( $saleReturn->status == 'Draft' && $user->can('delete_sale_order')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\SaleReturn  $saleReturn
     * @return mixed
     */
    public function restore(User $user, SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SaleReturn  $saleReturn
     * @return mixed
     */
    public function forceDelete(User $user, SaleReturn $saleReturn)
    {
        //
    }
}
