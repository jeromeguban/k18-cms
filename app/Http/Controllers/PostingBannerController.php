<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostingRequest;
use Illuminate\Http\Request;
use App\Models\Posting;
use App\Processes\PostingBannerProcess;

class PostingBannerController extends Controller
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
    public function index(Posting $posting)
    {
         // $this->authorize('view', $auction);

         $query = Posting::selectedFields()
            ->where('posting_id', $posting->posting_id)
            ->get();

        if(!$query){
            return 'Banners Not Found';
        }

        if($query) {
            return $query;
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Posting $posting)
    {
        $request->validate([
            'file'  => 'required|mimes:jpeg,png,jpg,JPG'
        ]);

        // return $request;
        $process = new PostingBannerProcess($posting, $request->file('file'));
        $process->upload();

        activity()
            ->performedOn( $posting)
            ->withProperties( $posting)
            ->log('Successfully added banner in Posting');

            return [
                'success'   => 1,
                'data'      => $posting->refresh()
            ];

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
            'banner'        =>  $request->image_name,
            'modified_by'   =>  auth()->id(),
        ]);

        activity()
            ->performedOn( $posting )
            ->withProperties($posting)
            ->log('Banner has been updated');

        return [
            'success' => 1,
            'data' => $posting
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PostingBannerProcess $process ,Posting $posting)
    {
        $process = new PostingBannerProcess($posting);
        $process->remove();

        activity()
            ->performedOn( $posting)
            ->withProperties($posting)
            ->log('succesfuly remove banner in posting');

        return ['success'   => 1];
    }
}
