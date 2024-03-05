<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use App\Models\SystemNotificationView;
use Illuminate\Http\Request;

class SystemNotificationViewedController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware(['auth']);
        
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('list', SystemNotification::class);
        
        $notifications = SystemNotification::selectedFields()
                            ->leftJoinViews()
                            ->whereNotYetViewed()
                            ->get();

        $data = $notifications->map(function($notification){
            return [
                'system_notification_id'    => $notification->id,
                'user_id'                   => auth()->id()
            ];
        })->toArray();

        SystemNotificationView::insert($data);

        return [
            'success' => 1
        ];
    }

  
   
}
