<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use SoftDeletes;

    protected $searchable_fields = [
        'name'
    ];


    public function scopeSelectFields($query)
    {
        $query->select([
            'couriers.icon',
            'couriers.id',
            'couriers.name', 
            'couriers.active',
            'couriers.vat', 
             DB::raw("IF(active = 1,'Active', 'InActive') AS `active_status`")
        ]);
    }

    // Relationships

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
