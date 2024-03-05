<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SystemNotification;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemNotificationPolicy extends Policy
{
    use HandlesAuthorization;

    protected $model = SystemNotification::class;

    /**
     * Determine whether the user can list system notifications.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->can('list.system-notification');
    }

    /**
     * Determine whether the user can view the system notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemNotification  $system_notification
     * @return mixed
     */
    public function view(User $user, SystemNotification $system_notification)
    {
        return $user->can('view.system-notification');
    }

    /**
     * Determine whether the user can create system notifications.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create.system-notification');
    }

    /**
     * Determine whether the user can update the system notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemNotification  $system_notification
     * @return mixed
     */
    public function update(User $user, SystemNotification $system_notification)
    {
        return $user->can('update.system-notification');
    }

    /**
     * Determine whether the user can delete the system notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemNotification  $system_notification
     * @return mixed
     */
    public function delete(User $user, SystemNotification $system_notification)
    {
        //
    }

    /**
     * Determine whether the user can restore the system notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemNotification  $system_notification
     * @return mixed
     */
    public function restore(User $user, SystemNotification $system_notification)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the system notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemNotification  $system_notification
     * @return mixed
     */
    public function forceDelete(User $user, SystemNotification $system_notification)
    {
        //
    }
}
