<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Event;

class EventActivityLogController extends Controller
{
    private static $event_model = 'App\Models\Event';

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Event $event)
    {
        $query = ActivityLog::selectedFields()
                            ->joinCreatedBy()
                            ->where('subject_id', $event->event_id)
                            ->where('subject_type', self::$event_model)
                            ->orderBy('activity_log.created_at', 'desc');

        return $this->response($query);
    }

    public function show(ActivityLog $activity_log)
    {
        return ActivityLog::selectedFields()
                ->joinCreatedBy()
                ->where('activity_log.id', $activity_log->id)
                ->first();
    }

}
