<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Auction;
use App\Processes\CatalogFileUploadProcess;
use App\Http\Requests\CatalogFileUploadRequest;

class CatalogFileUploadController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CatalogFileUploadRequest $request, Auction $auction)
    {
        $this->authorize('update', $auction);

        DB::transaction(function () use ($auction, $request) {

            if($request->file('file'))
                (new CatalogFileUploadProcess($auction, $request->file('file')))->upload();

            activity()
                ->performedOn($auction)
                ->withProperties($auction)
                ->log('Category Image has been updated');

            return [
                'success' => 1,
                'data' => $auction
            ];
        });
    }
}
