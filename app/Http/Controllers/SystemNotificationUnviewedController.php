<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;

class SystemNotificationUnviewedController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', SystemNotification::class);


        $query = SystemNotification::selectedFields()
                    ->leftJoinViews()
                    ->whereNotYetViewed();

        return $this->response($query);
    }

   
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemNotification  $systemNotification
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        $this->authorize('list', SystemNotification::class);


        return SystemNotification::selectedFields()
                    ->leftJoinViews()
                    ->whereNotYetViewed()
                    ->count();
    }

}
