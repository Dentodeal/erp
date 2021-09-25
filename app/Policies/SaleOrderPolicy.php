<?php

namespace App\Policies;

use App\SaleOrder;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaleOrderPolicy
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
     * @param  \App\SaleOrder  $saleOrder
     * @return mixed
     */
    public function view(User $user, SaleOrder $saleOrder)
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
     * @param  \App\SaleOrder  $saleOrder
     * @return mixed
     */
    public function update(User $user, SaleOrder $saleOrder)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SaleOrder  $saleOrder
     * @return mixed
     */
    public function delete(User $user, SaleOrder $saleOrder)
    {
        if(( $saleOrder->status == 'Draft' || $saleOrder->status == 'Rejected' ) && $user->can('delete_sale_order')){
            return true;
        }
        return false;
    }

    public function cancel(User $user, SaleOrder $saleOrder)
    {
        if(!( $saleOrder->shipment && $saleOrder->shipment->status === 'Complete')){
            return true;
        }
        return false;
    }
    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\SaleOrder  $saleOrder
     * @return mixed
     */
    public function restore(User $user, SaleOrder $saleOrder)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SaleOrder  $saleOrder
     * @return mixed
     */
    public function forceDelete(User $user, SaleOrder $saleOrder)
    {
        //
    }
}
