<?php

namespace App\Policies;

use App\ProductDepartment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductDepartmentPolicy
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
     * @param  \App\ProductDepartment  $productDepartment
     * @return mixed
     */
    public function view(User $user, ProductDepartment $productDepartment)
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
     * @param  \App\ProductDepartment  $productDepartment
     * @return mixed
     */
    public function update(User $user, ProductDepartment $productDepartment)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductDepartment  $productDepartment
     * @return mixed
     */
    public function delete(User $user, ProductDepartment $productDepartment)
    {
        if($productDepartment->products()->count() > 0){
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductDepartment  $productDepartment
     * @return mixed
     */
    public function restore(User $user, ProductDepartment $productDepartment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductDepartment  $productDepartment
     * @return mixed
     */
    public function forceDelete(User $user, ProductDepartment $productDepartment)
    {
        //
    }
}
