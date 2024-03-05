<?php

namespace App\Models;

use App\Traits\Pagination;
use Kodeine\Acl\Models\Eloquent\Permission as Model;

class Permission extends Model
{
    use Pagination;

    protected $searchable_fields = [
        'name',
    ];

    public function scopeWhereRoleId($query, $role_id)
    {
        return $query->whereHas('roles', function ($query) use ($role_id){
            $query->where('role_id', $role_id);
        });
    }

    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class, 'name', 'name');
    }
}
