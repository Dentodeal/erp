<?php

namespace App\Policies;

use App\Attribute;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttributePolicy
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
     * @param  \App\Attribute  $attribute
     * @return mixed
     */
    public function view(User $user, Attribute $attribute)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user,String $model)
    {
        if($user->hasRole('Super Admin'))
        {
            return true;
        }
        if($model == 'product'){
            if($user->can('create_product_attribute'))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Attribute  $attribute
     * @return mixed
     */
    public function update(User $user, Attribute $attribute, String $model)
    {
        if($user->hasRole('Super Admin'))
        {
            return true;
        }
        if($model == 'product'){
            if($user->can('update_product_attribute'))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Attribute  $attribute
     * @return mixed
     */
    public function delete(User $user, Attribute $attribute)
    {

        if($attribute->is_system == 'Yes'){
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
     * @param  \App\Attribute  $attribute
     * @return mixed
     */
    public function restore(User $user, Attribute $attribute)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Attribute  $attribute
     * @return mixed
     */
    public function forceDelete(User $user, Attribute $attribute)
    {
        //
    }
}
