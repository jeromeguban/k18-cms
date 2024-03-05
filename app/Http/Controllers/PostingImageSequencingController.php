<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostingRequest;
use Illuminate\Http\Request;
use App\Models\Posting;
use App\Models\Searchable\Posting as SearchablePosting;

class PostingImageSequencingController extends Controller
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Posting $posting)
    {
        $posting->update([
            'images'        =>   str_replace("//","/",json_encode($request->images)),
            'modified_by'   =>  auth()->id(),
        ]);

        SearchablePosting::where('posting_id', $posting->posting_id)->searchable();  

        activity()
            ->performedOn( $posting )
            ->withProperties($posting)
            ->log('Sequencing of Images has been changed.');

        return [
            'success' => 1,
            'data' => $posting
        ];
    }

    
}
