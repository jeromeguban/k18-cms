<?php

namespace App\Traits;

trait RolePermission
{
    public function generateRolePermission()
    {
        return $this->permissions()->create([
            'slug' => $this->generateSlugByModulePermissions(),
        ]);
    }

    public function addSlugInRolePermission(array $new_permissions)
    {
        $this->permissions->each(function ($permission, $key) use ($new_permissions) {
    
            $this->permissions()->where('id',$permission->id)->update([
                'slug' => $this->generateSlug($new_permissions)->merge($permission->slug)->toJson()
            ]);

        });
    }

    public function deleteSlugInRolePermission(string $deleted_permission)
    {
        $this->permissions->each(function ($permission, $key) use ($deleted_permission) {

            $slug = collect($permission->slug)->reject(function ($value, $key) use ($deleted_permission) {
                return $key == $deleted_permission;
            });

            $this->permissions()->where('id',$permission->id)->update([
                'slug' => $slug
            ]);
        });
    }

    public function generateSlug(array $new_permissions)
    {
        return collect($new_permissions)->mapWithKeys(function ($item, $key) use ($new_permissions) {

            if(is_array($item)) {
                return [ $item['permission'] => false ];
            }

            return [ $item => false ];
        });
    }

    public function generateSlugByModulePermissions()
    {
        return $this->modulePermissions->mapWithKeys(function ($item, $key) {
            return [ $item['permission'] => false ];
        })->toArray();
    }
}