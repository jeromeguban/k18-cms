<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AccessRequestEmailTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccessRequestEmailTemplatePolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = AccessRequestEmailTemplate::class;

    /**
     * Determine whether the user can list access request email template.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  
        return $user->hasPermission('list.access-request-email-template');
    }

    /**
     * Determine whether the user can view the access request email template.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRequestEmailTemplate  $access-request-email-template
     * @return mixed
     */
    public function view(User $user, AccessRequestEmailTemplate $access_request_email_template)
    {
        return $user->hasPermission('view.access-request-email-template');
    }

    /**
     * Determine whether the user can create access request email template.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.access-request-email-template');
    }

    /**
     * Determine whether the user can update the access request email template
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRequestEmailTemplate  $access-request-email-template
     * @return mixed
     */
    public function update(User $user, AccessRequestEmailTemplate $access_request_email_template)
    {
        return $user->hasPermission('update.access-request-email-template');
    }

    /**
     * Determine whether the user can delete the access request email template
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRequestEmailTemplate  $access-request-email-template
     * @return mixed
     */
    public function delete(User $user, AccessRequestEmailTemplate $access_request_email_template)
    {
        return $user->hasPermission('delete.access-request-email-template');
    }

}
