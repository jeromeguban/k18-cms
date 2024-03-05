<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PostingInquiry;

class PostingInquiryActivityLogController extends Controller
{
    private static $model = 'App\Models\PostingInquiry';
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
    public function index(PostingInquiry $posting_inquiry)
    {
        $query = ActivityLog::selectedFields()
            ->joinCreatedBy()
            ->where('subject_type', self::$model)
            ->where('subject_id', $posting_inquiry->id)
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
