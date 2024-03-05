<?php

namespace App\Listeners;

use App\Models\Role;
use App\Models\Permission;
use App\Events\ModuleHasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PermissionsCreationAndAssignToRoles
{
    protected $roles;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->roles = Role::all();
    }

    /**
     * Handle the event.
     *
     * @param  ModuleHasCreated  $event
     * @return void
     */
    public function handle(ModuleHasCreated $event)
    {
        foreach ($this->roles as $role) {
            
            $permission = $event->module->generateRolePermission();

            $role->assignPermission($permission);
        }
    }
}
