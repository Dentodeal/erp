<?php

namespace App\Policies;

use App\ProductAttributeSet;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductAttributeSetPolicy
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
     * @param  \App\ProductAttributeSet  $productAttributeSet
     * @return mixed
     */
    public function view(User $user, ProductAttributeSet $productAttributeSet)
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
     * @param  \App\ProductAttributeSet  $productAttributeSet
     * @return mixed
     */
    public function update(User $user, ProductAttributeSet $productAttributeSet)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductAttributeSet  $productAttributeSet
     * @return mixed
     */
    public function delete(User $user, ProductAttributeSet $productAttributeSet)
    {
        if($productAttributeSet->is_system){
            return false;
        }
        if($productAttributeSet->products()->count() > 0)
        {
            return false;
        }
        if($user->hasRole('Super Admin'))
        {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductAttributeSet  $productAttributeSet
     * @return mixed
     */
    public function restore(User $user, ProductAttributeSet $productAttributeSet)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProductAttributeSet  $productAttributeSet
     * @return mixed
     */
    public function forceDelete(User $user, ProductAttributeSet $productAttributeSet)
    {
        //
    }
}
