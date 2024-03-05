<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EventAccessRequestTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventAccessRequestTemplatePolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = EventAccessRequestTemplate::class;

    /**
     * Determine whether the user can list access request email template.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {  
        return $user->hasPermission('list.event-access-request-template');
    }

    /**
     * Determine whether the user can view the access request email template.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EventAccessRequestTemplate  $event-access-request-template
     * @return mixed
     */
    public function view(User $user, EventAccessRequestTemplate $event_access_request_template)
    {
        return $user->hasPermission('view.event-access-request-template');
    }

    /**
     * Determine whether the user can create access request email template.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create.event-access-request-template');
    }

    /**
     * Determine whether the user can update the access request email template
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EventAccessRequestTemplate  event-access-request-template
     * @return mixed
     */
    public function update(User $user, EventAccessRequestTemplate $event_access_request_template)
    {
        return $user->hasPermission('update.event-access-request-template');
    }

    /**
     * Determine whether the user can delete the access request email template
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EventAccessRequestTemplate  event-access-request-template
     * @return mixed
     */
    public function delete(User $user, EventAccessRequestTemplate $event_access_request_template)
    {
        return $user->hasPermission('delete.event-access-request-template');
    }

}
