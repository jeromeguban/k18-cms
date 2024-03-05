<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Career;
use App\Models\CareerApplicant;
use Illuminate\Auth\Access\HandlesAuthorization;

class CareerApplicantPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = CareerApplicant::class;

    /**
    * Determine whether the user hasPermission list career.
    *
    * @param  \App\Models\User  $user
    * @return mixed
    */
    public function list(User $user)
    {
        return $user->hasPermission('list.career-applicant');
    }

    /**
     * Determine whether the user hasPermission view the career.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CareerApplicant  $career_applicant
     * @return mixed
     */
    public function view(User $user, CareerApplicant $career_applicant)
    {
        return $user->hasPermission('view.career-applicant');
    }

    /**
     * Determine whether the user hasPermission create career.
     *
     * @param  \App\Models\CareerApplicant  $career_applicant
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.career-applicant');

    }

    /**
     * Determine whether the user hasPermission update the career.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CareerApplicant  $career_applicant
     * @return mixed
     */
    public function update(User $user, CareerApplicant $career_applicant)
    {
        return $user->hasPermission('update.career-applicant');
    }
    /**
     * Determine whether the user hasPermission delete the career.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CareerApplicant  $career_applicant
     * @return mixed
     */
    public function delete(User $user, CareerApplicant $career_applicant)
    {
        return $user->hasPermission('delete.career-applicant');
    }
}
