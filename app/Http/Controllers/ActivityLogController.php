<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Services\ActivityLogService;
use App\Models\ItemStatus;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    const BASE_URL = "/dashboard#/";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = Carbon::parse(Now())->toDateString();

        $query = ActivityLog::selectedFields()
                ->joinCreatedBy()
                ->orderBy('activity_log.created_at', 'desc');

        if(!request()->from && !request()->to) {

            $query->where('activity_log.created_at', 'LIKE', "%".$date."%");
            return $this->response($query);
        }

        if(request()->from && request()->to) {

            $from = Carbon::parse(request()->from.' 00:00:00')->toDateTimeString();
            $to   = Carbon::parse(request()->to.' 24:59:59')->toDateTimeString();
            $query->whereBetween('activity_log.created_at',[$from, $to]);
            return $this->response($query);

        }

    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLog $activity_log)
    {
        return ActivityLog::selectedFields()
                ->joinCreatedBy()
                ->where('activity_log.id', $activity_log->id)
                ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
