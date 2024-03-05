<?php

namespace App\Models;

class StoreCourier extends Model
{

    protected $primaryKey = "id";

    protected $fillable = [
        'store_id',
        'courier_id',
        'active',
        'created_at',
        'updated_at',
    ];

    protected $searchable_fields = [
        'couriers.name',
    ];

    //join

    public function scopeJoinCouriers($query)
    {
        $query->addSelect([
            'couriers.name as courier_name',
        ]);


        $query->join('couriers', 'couriers.id', '=', 'store_couriers.courier_id');

        return $query;
    }



    // Relationships
    public function courier()
    {
        return $this->hasMany(Courier::class, 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Store::class, 'id');
    }
}
