<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class SystemNotification extends Model
{
    

    public function scopeLeftJoinViews($query)
    {
    	$query->addSelect([
    		DB::raw("IF(system_notification_views.id IS NOT NULL, 1, 0) AS viewed")
    	]);

    	$query->leftJoin('system_notification_views', function($join){
    		$join->on('system_notification_views.system_notification_id', '=', 'system_notifications.id');
    		$join->where('system_notification_views.user_id', auth()->id());
    	});


        $query->where(function($query) {
            $query->where(function($query){
                $query->whereIn('system_notifications.channel', auth()->user()->getActiveNotificationChannels())
                    ->where('system_notifications.store_id', session()->get('store_id'));
            });

            $query->orWhere('system_notifications.to', auth()->id());
        });

    }


    public function scopeJoinStore($query)
    {

        $query->addSelect([
            DB::raw("stores.code")
        ]);

        $query->join('stores', 'stores.id', '=', 'system_notifications.store_id');

        return $query;
        
    }


    public function scopeLeftJoinUser($query)
    {

        $query->addSelect([
            DB::raw(" CONCAT(users.first_name, ' ', users.last_name) AS user_name ")
        ]);

        $query->leftjoin('users', 'users.id', '=', 'system_notifications.to');

        return $query;
        
    }
   


    public function scopeWhereNotYetViewed($query)
    {
        $query->whereNull('system_notification_views.id');
    }

    public function scopeWithLimit($query)
    {
        $query->when(request()->limit, function($query){
            $query->limit(request()->limit);
        });
    }
}
