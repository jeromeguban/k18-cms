<?php

namespace App\Models;

use App\Traits\RolePermission;

class Module extends Model
{
    use RolePermission;

    protected $searchable_fields = [
        'name'
    ];

    public function addPermissions(array $module_permissions)
    {
        if(count($module_permissions) > 1 || (isset($module_permissions[0]) && is_array($module_permissions[0])) ) {
            return $this->modulePermissions()->createMany($module_permissions);
        }

        return $this->modulePermissions()->create($module_permissions);
    }

    // Relationships
    public function modulePermissions()
    {
        return $this->hasMany(ModulePermission::class, 'module_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'name', 'name');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
