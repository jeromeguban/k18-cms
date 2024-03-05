<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Term;
use Illuminate\Auth\Access\HandlesAuthorization;

class TermPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Term::class;

     /**
    * Determine whether the user can list term.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.term');
    }

    /**
     * Determine whether the user can view the term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Term  $term
     * @return mixed
     */
    public function view(User $user, Term $term)
    {
        return $user->hasPermission('view.term');
    }

    /**
     * Determine whether the user can create permissions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.term');
    }

    /**
     * Determine whether the user can update the term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Term  $term
     * @return mixed
     */
    public function update(User $user, Term $term)
    {
        return $user->hasPermission('update.term');
    }

    /**
     * Determine whether the user can delete the term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Term  $term
     * @return mixed
     */
    public function delete(User $user, Term $term)
    {
        return $user->hasPermission('delete.term');
    }

    /**
     * Determine whether the user can restore the term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Term  $term
     * @return mixed
     */
    public function restore(User $user, Term $term)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Model\Term  $term
     * @return mixed
     */
    public function forceDelete(User $user, Term $term)
    {
        //
    }
}
