<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
class ActivityLog extends Model
{
	protected $table = "activity_log";

	protected $searchable_fields = [
        'activity_log.description',
    ];

    public function scopeJoinCreatedBy($query)
    {
    	$query->addSelect([
    		  DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS created_by")
    	]);

    	$query->leftJoin('users', 'activity_log.causer_id', '=', 'users.id');

    	return $query;
    }
}
