<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Brand;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy extends Policy
{

    use HandlesAuthorization;

    protected $model = Brand::class;

    /**
     * Determine whether the user hasPermission list brands.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  

        return $user->hasPermission('list.brand');

    }

    /**
     * Determine whether the user hasPermission view the brand.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function view(User $user, Brand $brand)
    {

        return $user->hasPermission('view.brand');

    }

    /**
     * Determine whether the user hasPermission create brands.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.brand');

    }

    /**
     * Determine whether the user hasPermission update the brand.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function update(User $user, Brand $brand)
    {

        return $user->hasPermission('update.brand');

    }

    /**
     * Determine whether the user hasPermission delete the brand.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function delete(User $user, Brand $brand)
    {

        return $user->hasPermission('delete.brand');

    }

    /**
     * Determine whether the user hasPermission restore the brand.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function restore(User $user, Brand $brand)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the brand.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function forceDelete(User $user, Brand $brand)
    {
        //
    }

}