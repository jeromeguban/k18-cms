<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;

class ProfileActivityLogController extends Controller
{
    private static $user_model = 'App\Models\User';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
    public function index(User $user)
    {
        // return 'awit';

        // return $user;

        $query = ActivityLog::selectedFields()
                            ->joinCreatedBy()
                            ->where('causer_id', ($user->id))
                            ->where('causer_type', self::$user_model)
                            ->orderBy('activity_log.created_at', 'desc');

        return $this->response($query);
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActivityLog  $activity_log
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLog $activity_log)
    {
        return ActivityLog::selectedFields()
                ->joinCreatedBy()
                ->where('activity_log.id', $activity_log->id)
                ->first();
    }
}
