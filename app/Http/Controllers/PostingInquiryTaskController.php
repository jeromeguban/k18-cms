<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostingInquiryTaskRequest;
use App\Models\PostingInquiry;
use App\Models\PostingInquiryTask;
use Illuminate\Http\Request;

class PostingInquiryTaskController extends Controller
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
    public function index(Request $request, PostingInquiry $posting_inquiry)
    {
        $query = PostingInquiryTask::selectedFields()
            ->where('posting_inquiry_tasks.posting_inquiry_id', $posting_inquiry->id)
            ->sortable()
            ->searchable();

        $query->when($request->status, function ($query) use ($request) {
            $query->where('posting_inquiry_tasks.status', $request->status);
        });

        return $this->response($query);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostingInquiryTaskRequest $request, PostingInquiryTask $posting_inquiry_task)
    {

        $posting_inquiry_task->update([
            'task' => json_encode($request->task),
        ]);

        $posting_inquiry = PostingInquiry::where('id', $posting_inquiry_task->posting_inquiry_id)->first();

        activity()
            ->performedOn($posting_inquiry)
            ->withProperties($posting_inquiry_task)
            ->log('Tasks Successfully Updated');

        return [
            'success' => 1,
            'data' => $posting_inquiry,
        ];
    }

}
