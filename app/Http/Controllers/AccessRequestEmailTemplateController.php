<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessRequestEmailTemplate;

class AccessRequestEmailTemplateController extends Controller
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
        $this->authorize('list', AccessRequestEmailTemplate::class);

        $query = AccessRequestEmailTemplate::selectedFields()
                    ->joinAuction()
                    ->searchable()
                    ->orderBy('access_request_email_templates.id', 'DESC');

        return $this->response($query);
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->authorize('create', AccessRequestEmailTemplate::class);

        $request->validate([
            'auction_id' => 'required',
            'title'      => 'required',
            'body'       => 'required',
            'type'       => 'required'
        ], [],[
            'auction_id'    => 'Auction',
            'title'         => 'Title',
            'body'          => 'Body',
            'type'          => 'Type'
        ]);

        $access_request_email_template = AccessRequestEmailTemplate::create([
            'auction_id'   => $request->auction_id,
            'title'        => $request->title,
            'body'         => $request->body,
            'type'         => $request->type,
            'created_by'   => auth()->id(),
            'modified_by'  => auth()->id(),
        ]);

        activity()
            ->performedOn($access_request_email_template)
            ->withProperties($access_request_email_template)
            ->log('Email Template has been created');

        return [
            'success' => 1,
            'data' => $access_request_email_template
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request, AccessRequestEmailTemplate $access_request_email_template)
    {
        
        $this->authorize('update', $access_request_email_template);

        $request->validate([
            'auction_id' => 'required',
            'title'      => 'required',
            'body'       => 'required',
            'type'       => 'required'
        ], [], [
            'auction_id'    => 'Auction',
            'title'         => 'Title',
            'body'          => 'Body',
            'type'          => 'Type'
        ]);

        $access_request_email_template->update([
            'auction_id'   => $request->auction_id,
            'title'        => $request->title,
            'body'         => $request->body,
            'type'         => $request->type,
            'modified_by'  => auth()->id(),
        ]);

        activity()
            ->performedOn($access_request_email_template)
            ->withProperties($access_request_email_template)
            ->log('Email Template has been updated');

        return [
            'success' => 1,
            'data'    => $access_request_email_template
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessRequestEmailTemplate  $access_request_email_template
     * @return \Illuminate\Http\Response
     */
    public function show(AccessRequestEmailTemplate $access_request_email_template)
    {
         $this->authorize('view', $access_request_email_template);

        return AccessRequestEmailTemplate::selectedFields()
            ->where('id', $access_request_email_template->id)
            ->joinAuction()
            ->withRelations()
            ->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccessRequestEmailTemplate $access_request_email_template)
    {
        
        $this->authorize('delete', $access_request_email_template);
     
        $access_request_email_template->update([
            'deleted_by'   => auth()->id()
        ]);

        $access_request_email_template->delete();

        activity()
            ->performedOn($access_request_email_template)
            ->withProperties($access_request_email_template)
            ->log('Email Template has been Delete');

        return [ 
            'success' => 1 
        ];

    }
}
