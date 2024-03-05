<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuctionSection;
use App\Processes\AuctionSectionImageProcess;

class AuctionSectionImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AuctionSection $auction_section)
    {
        $request->validate([
            'file'  => 'required|mimes:jpeg,png,jpg,JPG'
        ]);

        $process = new AuctionSectionImageProcess($auction_section, $request->file('file'));
        $process->upload();

        activity()
            ->performedOn($auction_section)
            ->withProperties($auction_section)
            ->log('Successfully Uploaded');

        return [
            'success'   => 1,
            'data'      =>  $process->upload()
        ];
    }
}
