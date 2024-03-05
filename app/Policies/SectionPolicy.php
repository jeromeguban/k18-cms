<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Section;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectionPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Section::class;
    

    /**
    * Determine whether the user hasPermission list section.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function access(User $user)
    {
        return $user->hasPermission('access.section');
    }


    /**
    * Determine whether the user hasPermission list section.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.section');
    }

    /**
     * Determine whether the user hasPermission view the section.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Section  $section
     * @return mixed
     */
    public function view(User $user, Section $section)
    {
        return $user->hasPermission('view.section');
    }

    /**
     * Determine whether the user hasPermission create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.section');
    }

    /**
     * Determine whether the user hasPermission update the section.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Section  $section
     * @return mixed
     */
    public function update(User $user, Section $section)
    {
        return $user->hasPermission('update.section');
    }

    /**
     * Determine whether the user hasPermission delete the section.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Section  $section
     * @return mixed
     */
    public function delete(User $user, Section $section)
    {
        return $user->hasPermission('delete.section');
    }

    /**
     * Determine whether the user hasPermission restore the section.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Section  $section
     * @return mixed
     */
    public function restore(User $user, Section $section)
    {
        //
    }

    /**
     * Determine whether the user hasPermission permanently delete the section.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Section  $section
     * @return mixed
     */
    public function forceDelete(User $user, Section $section)
    {
        //
    }
}
