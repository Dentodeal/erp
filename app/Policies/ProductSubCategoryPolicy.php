<?php

namespace App\Policies;

use App\ProductSubCategory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductSubCategoryPolicy
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
     * @param  \App\ProductSubCategory  $productSubCategory
     * @return mixed
     */
    public function view(User $user, ProductSubCategory $productSubCategory)
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
     * @param  \App\ProductSubCategory  $productSubCategory
     * @return mixed
     */
    public function update(User $user, ProductSubCategory $productSubCategory)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductSubCategory  $productSubCategory
     * @return mixed
     */
    public function delete(User $user, ProductSubCategory $productSubCategory)
    {
        if($productSubCategory->products()->count() > 0){
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductSubCategory  $productSubCategory
     * @return mixed
     */
    public function restore(User $user, ProductSubCategory $productSubCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductSubCategory  $productSubCategory
     * @return mixed
     */
    public function forceDelete(User $user, ProductSubCategory $productSubCategory)
    {
        //
    }
}
