<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuctionSection;
use Illuminate\Support\Facades\DB;
use App\Processes\AuctionSectionProcess;
use App\Http\Requests\AuctionSectionRequest;


class AuctionSectionController extends Controller
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
    public function index(Request $request)
    {

        $this->authorize('list', AuctionSection::class);

        $query = AuctionSection::selectedFields()
            ->searchable()
            ->orderBy('id', 'ASC');

        // dd(str_replace(["\"[{", "}]\""], ["[{", "}]"], str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($query->properties))))));

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuctionSectionRequest $request, AuctionSectionProcess $process)
    {
        $this->authorize('create', AuctionSection::class);

        $process->create();

        activity()
            ->performedOn($process->auction_section())
            ->withProperties($process->auction_section())
            ->log('Auction Section has been created');

        return [
            'success' => 1,
            'data' => $process->auction_section()
        ];
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AuctionSection $auction_section)
    {

        $this->authorize('view', $auction_section);

        return AuctionSection::selectedFields()
            ->where('id', $auction_section->id)
            ->first();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuctionSectionRequest $request,  AuctionSectionProcess $process, AuctionSection $auction_section)
    {
        $this->authorize('update', $auction_section);

        $process->find($auction_section->id)
            ->update();

        activity()
            ->performedOn($process->auction_section())
            ->withProperties($process->auction_section())
            ->log('Auction Section has been updated');

        return [
            'data'      => $process->auction_section(),
            'success'   => 1
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuctionSection $auction_section)
    {
        $this->authorize('delete', $auction_section);

        $auction_section->delete();

        activity()
            ->performedOn($auction_section)
            ->withProperties($auction_section)
            ->log('Auction Section has been deleted');

        return ['success' => 1];
    }
}
