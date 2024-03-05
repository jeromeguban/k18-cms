<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SubCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubCategoryPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = SubCategory::class;

    /**
     * Determine whether the user can list account executives.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('list.sub-categories');
    }

    /**
     * Determine whether the user can view the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubCategory  $sub_category
     * @return mixed
     */
    public function view(User $user, SubCategory  $sub_category)
    {
        return $user->hasPermission('view.sub-categories');
    }

    /**
     * Determine whether the user can create account executives.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.sub-categories');
    }

    /**
     * Determine whether the user can update the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubCategory  $  $sub_category
     * @return mixed
     */
    public function update(User $user, SubCategory   $sub_category)
    {
        return $user->hasPermission('update.sub-categories');
    }

    /**
     * Determine whether the user can update the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubCategory  $  $sub_category
     * @return mixed
     */
    public function migration(User $user, SubCategory   $sub_category)
    {
        return $user->hasPermission('migration.sub-categories');
    }

    /**
     * Determine whether the user can delete the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubCategory  $  $sub_category
     * @return mixed
     */
    public function delete(User $user, SubCategory   $sub_category)
    {
        return $user->hasPermission('delete.sub-categories');
    }

    /**
     * Determine whether the user can restore the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubCategory  $  $sub_category
     * @return mixed
     */
    public function restore(User $user, SubCategory   $sub_category)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the account executive.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubCategory  $  $sub_category
     * @return mixed
     */
    public function forceDelete(User $user, SubCategory  $sub_category)
    {
              //
    }
}
