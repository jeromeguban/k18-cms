<?php

namespace App\Models;


class CourierStatuses extends Model
{
    public function scopeWhereQueryScopes($query)
    {
        if(request()->courier_id){
            $query->where('courier_id', request()->courier_id);
        }
    }
    public function scopeJoinDeliveryStatus($query)
    {
    	$query->addSelect([
    		'delivery_statuses.code as inhouse_code'
    	]);

    	$query->join('delivery_statuses', 'delivery_statuses.id', '=', 'courier_statuses.status_id');

    	return $query;
    }

    public function scopeJoinCouriers($query)
    {
    	$query->addSelect([
    		'couriers.name as courier_name'
    	]);

    	$query->join('couriers', 'couriers.id', '=', 'courier_statuses.courier_id');

    	return $query;
    }


}
