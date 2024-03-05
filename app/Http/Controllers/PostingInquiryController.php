<?php

namespace App\Http\Controllers;

use App\Helpers\Gmail;
use App\Helpers\ModelDecrypter;
use App\Http\Requests\PostingInquiryChecklistRequest;
use App\Models\AccountExecutive;
use App\Models\PostingInquiry;
use App\Models\PostingInquiryTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostingInquiryController extends Controller
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
        $this->authorize('list', PostingInquiry::class);

        $query = PostingInquiry::selectedFields()
            ->leftJoinPosting()
            ->leftJoinAccountExecutive()
            ->whereNull('posting_inquiries.store_id')
            ->whereQueryScopes()
            ->sortable()
            ->searchable();

        // return $this->response($query);

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($posting_inquiry) {
            $posting_inquiry = (new ModelDecrypter)->decryptModel($posting_inquiry);
        });

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inquiry  $inquiries
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', PostingInquiry::findOrFail($id));

        $posting_inquiry = PostingInquiry::where('id', $id)
            ->selectedFields()
            ->leftJoinPosting()
            ->whereNull('posting_inquiries.store_id')
            ->withRelations()
            ->get();

        return (new ModelDecrypter)->decryptCollection($posting_inquiry);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setPriorityStatus(Request $request, PostingInquiry $posting_inquiry)
    {
        $this->authorize('update', $posting_inquiry);

        activity()
            ->performedOn($posting_inquiry)
            ->withProperties($posting_inquiry)
            ->log('Set the Priority from' . ' ' . $posting_inquiry->priority . ' ' . 'to' . ' ' . $request->priority);

        $posting_inquiry->update([
            'priority' => $request->priority,
        ]);

        return [
            'success' => 1,
            'data' => $posting_inquiry,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setStatus(Request $request, PostingInquiry $posting_inquiry)
    {
        $this->authorize('update', $posting_inquiry);

        activity()
            ->performedOn($posting_inquiry)
            ->withProperties($posting_inquiry)
            ->log('Set the status from' . ' ' . $posting_inquiry->status . ' ' . 'to' . ' ' . $request->status);

        $posting_inquiry->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return [
            'success' => 1,
            'data' => $posting_inquiry,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignAccountExecutive(Request $request, PostingInquiry $posting_inquiry)
    {
        $this->authorize('update', $posting_inquiry);

        $posting_inquiry->update([
            'account_executive_id' => $request->account_executive_id,
        ]);

        $account_executive = AccountExecutive::where('id', $request->account_executive_id)->first();

        $this->sendEmailNotification($account_executive, $posting_inquiry);
        $this->createPostingInquiryTasks($posting_inquiry);

        activity()
            ->performedOn($posting_inquiry)
            ->withProperties($posting_inquiry)
            ->log('Assigned to' . ' ' . $account_executive->first_name . ' ' . $account_executive->last_name);

        return [
            'success' => 1,
            'data' => $posting_inquiry,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateChecklist(PostingInquiryChecklistRequest $request, PostingInquiry $posting_inquiry)
    {
        $this->authorize('update', $posting_inquiry);

        $posting_inquiry->update([
            'checklist' => json_encode($request->checklist),
        ]);

        activity()
            ->performedOn($posting_inquiry)
            ->withProperties($posting_inquiry)
            ->log('Checklist Successfully Updated');

        return [
            'success' => 1,
            'data' => $posting_inquiry,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostingInquiry  $posting_inquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostingInquiry $posting_inquiry)
    {
        $this->authorize('delete', $posting_inquiry);

        DB::transaction(function () use ($posting_inquiry) {

            $posting_inquiry->update([
                'deleted_by' => auth()->id(),
            ]);

            $posting_inquiry->delete();
            activity()
                ->performedOn($posting_inquiry)
                ->log('Posting Inquiry has been deleted');
        });

        return ['success' => 1];
    }

    private function sendEmailNotification($account_executive, $posting_inquiry)
    {
        $user = User::where('id', $account_executive->user_id)->first();

        $data = PostingInquiry::where('id', $posting_inquiry->id)
            ->selectedFields()
            ->leftJoinPosting()
            ->withRelations()
            ->get();

        $data = (new ModelDecrypter)->decryptCollection($data);

        (new Gmail())->to(strtolower($user->email))
            ->view('emails.account-executive-notification')
            ->with([
                'email' => $user->email,
                'inquiries' => $data,
                'subject' => 'You have been Assigned for this Inquiry',
            ])
            ->send();

    }

    private function createPostingInquiryTasks($posting_inquiry)
    {
        $statuses = ['In Progress', 'Answered', 'On Hold', 'Cancelled', 'Closed'];

        $task = PostingInquiryTask::where('posting_inquiry_id', $posting_inquiry->id)->get();

        if (!$task->count()) {
            foreach ($statuses as $status) {
                PostingInquiryTask::create(
                    [
                        'posting_inquiry_id' => $posting_inquiry->id,
                        'status' => $status,
                        'task' => json_encode([]),
                    ]
                );
            }
        }

    }
}
