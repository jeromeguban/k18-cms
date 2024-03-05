<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostingInquiry;
use App\Helpers\ModelDecrypter;
use App\Exports\StoreInquiriesExport;

class StoreInquiriesController extends Controller
{
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
    public function index()
    {
        $query = PostingInquiry::selectedFields()
                            ->leftJoinPosting()
                            ->whereQueryScopes()
                            ->where('posting_inquiries.store_id', session()->get('store_id'))
                            ->sortable();

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($posting_inquiry) {
            $posting_inquiry =   (new ModelDecrypter)->decryptModel($posting_inquiry);
        });

       return $response;

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
    public function show($id)
    {
        $this->authorize('view', PostingInquiry::findOrFail($id));

        $query = PostingInquiry::selectedFields()
                            ->leftJoinPosting()
                            ->where('id', $id)
                            ->withRelations();


        return (new ModelDecrypter)->decryptCollection($query->get())->toJson();
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
    public function updateStatus(Request $request, PostingInquiry $posting_inquiry)
    {
        $request->validate([
            'status'    => 'required',
            'remarks'   => 'required',
        ], [], [
            'status'    => 'Status',
            'remarks'   => 'Remarks'
        ]);

        $posting_inquiry->update([
            'status'    => $request->status,
            'remarks'   => $request->remarks
        ]);

        activity()
            ->performedOn( $posting_inquiry)
            ->withProperties( $posting_inquiry)
            ->log('Status has been updated');

        return [
            'success'   => 1,
            'data'      => $posting_inquiry
        ];
    }

    public function export()
    {
        return \Excel::download(new StoreInquiriesExport,
            'Store Inquiries Report - '.now()->toDateTimeString().'.xlsx');
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
