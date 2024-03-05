<?php

namespace App\Http\Controllers;

use App\Http\Requests\AffiliateMarketingRequest;
use App\Models\AffiliateMarketing;
use App\Processes\AffiliateMarketingProcess;
use Illuminate\Http\Request;

class AffiliateMarketingController extends Controller
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
        $this->authorize('list', AffiliateMarketing::class);

        $query = AffiliateMarketing::withRelations()
            ->searchable()
            ->orderBy('affiliate_marketings.created_at', 'desc')
            ->sortable();

        return $this->response($query);
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
    public function store(AffiliateMarketingRequest $request, AffiliateMarketingProcess $process)
    {
        $this->authorize('create', AffiliateMarketing::class);

        $process->create();

        activity()
            ->performedOn( $process->affiliate_marketing() )
            ->withProperties($process->affiliate_marketing())
            ->log('AffiliateMarketing has been created');

        return [
            'success' => 1,
            'data' => $process->affiliate_marketing()
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AffiliateMarketing  $affiliate_marketing
     * @return \Illuminate\Http\Response
     */
    public function show(AffiliateMarketing $affiliate_marketing)
    {
        $this->authorize('view', $affiliate_marketing);

        return AffiliateMarketing::where('id', $affiliate_marketing->id)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AffiliateMarketing  $affiliate_marketing
     * @return \Illuminate\Http\Response
     */
    public function edit(AffiliateMarketing $affiliate_marketing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AffiliateMarketing  $affiliate_marketing
     * @return \Illuminate\Http\Response
     */
    public function update(AffiliateMarketingRequest $request, AffiliateMarketingProcess $process, AffiliateMarketing $affiliate_marketing)
    {
        $this->authorize('update', $affiliate_marketing);

        $process->find($affiliate_marketing->id)->update();

        activity()
            ->performedOn( $process->affiliate_marketing() )
            ->withProperties($process->affiliate_marketing())
            ->log('AffiliateMarketing has been updated');

        return [
            'success' => 1,
            'data' => $process->affiliate_marketing()
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setActive(Request $request, AffiliateMarketing $marketer)
    {
        $this->authorize('update', $marketer);
        $marketer->update([
            'active'   => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn($marketer)
            ->withProperties($marketer)
            ->log('Voucher has been updated');

        return [
            'success' => 1,
            'data' => $marketer
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AffiliateMarketing  $affiliate_marketing
     * @return \Illuminate\Http\Response
     */
    public function destroy(AffiliateMarketingProcess $process, AffiliateMarketing $affiliate_marketing)
    {
        $this->authorize('delete', $affiliate_marketing);

        $process->find($affiliate_marketing->id)->delete();

        activity()
            ->performedOn( $process->affiliate_marketing() )
            ->withProperties($process->affiliate_marketing())
            ->log('AffiliateMarketing has been deleted');

        return ['success' => 1];
    }
}
