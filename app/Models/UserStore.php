<?php

namespace App\Models;

class UserStore extends Model
{
    
    public function scopeJoinStore($query)
    {
    	$query->addSelect([
    		'stores.*'
    	]);
    	$query->join('stores', 'stores.id', '=', 'user_stores.store_id');

    	return $query;
    }
}
