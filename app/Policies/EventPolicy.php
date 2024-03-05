<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = Event::class;

    /**
     * Determine whether the user hasPermission access the orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function access(User $user, Event $event)
    {

        return $user->hasPermission('access.event');

    }

    /**
     * Determine whether the user hasPermission list event.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {

        return $user->hasPermission('list.event');

    }

    /**
     * Determine whether the user hasPermission view the orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function view(User $user, Event $event)
    {

        return $user->hasPermission('view.event');

    }

    /**
     * Determine whether the user hasPermission create brands.
     *
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasPermission('create.event');

    }

    /**
     * Determine whether the user hasPermission update the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        return $user->hasPermission('update.event');
    }

    /**
     * Determine whether the user hasPermission delete the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function delete(User $user, Event $event)
    {
        return $user->hasPermission('delete.event');
    }

    /**
     * Determine whether the user hasPermission publish the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function publish(User $user, Event $event)
    {
        return $user->hasPermission('publish.event');
    }
    /**
     * Determine whether the user hasPermission unpublish the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function unpublish(User $user, Event $event)
    {
        return $user->hasPermission('unpublish.event');
    }

    /**
     * Determine whether the user hasPermission view-event-log the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function viewEventLog(User $user, Event $event)
    {
        return $user->hasPermission('view-event-log.event');
    }

    /**
     * Determine whether the user hasPermission hold-posting the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function holdPosting(User $user, Event $event)
    {
        return $user->hasPermission('hold-posting.event');
    }


}
