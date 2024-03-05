<?php

namespace App\Listeners;

use App\Models\Module;
use App\Models\Role;
use App\Models\Permission;
use App\Events\RoleHasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PermissionsCreation
{
    protected $modules;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->modules  = Module::all();
    }

    /**
     * Handle the event.
     *
     * @param  RoleHasCreated  $event
     * @return void
     */
    public function handle(RoleHasCreated $event)
    {
    
        foreach ($this->modules as $module) {

            $permission = $module->generateRolePermission();

            // Code below doesn't work. dont know why.
            // $event->role->assignPermission($permission);

            // This works ^_^
            Role::find($event->role->id)->assignPermission($permission);
        }
    }
}
