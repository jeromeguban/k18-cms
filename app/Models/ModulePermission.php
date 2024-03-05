<?php

namespace App\Models;

class ModulePermission extends Model
{
    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
