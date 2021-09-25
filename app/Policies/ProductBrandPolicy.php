<?php

namespace App\Policies;

use App\ProductBrand;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductBrandPolicy
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
     * @param  \App\ProductBrand  $productBrand
     * @return mixed
     */
    public function view(User $user, ProductBrand $productBrand)
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
     * @param  \App\ProductBrand  $productBrand
     * @return mixed
     */
    public function update(User $user, ProductBrand $productBrand)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductBrand  $productBrand
     * @return mixed
     */
    public function delete(User $user, ProductBrand $productBrand)
    {
        if($productBrand->products()->count() > 0){
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductBrand  $productBrand
     * @return mixed
     */
    public function restore(User $user, ProductBrand $productBrand)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductBrand  $productBrand
     * @return mixed
     */
    public function forceDelete(User $user, ProductBrand $productBrand)
    {
        //
    }
}
