<?php

namespace App\Http\Controllers;

use App\Models\Posting;
use App\Models\PostingTag;
use Illuminate\Http\Request;
use App\Models\PostingFilter;
use App\Processes\PostingProcess;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostingRequest;
use Illuminate\Support\Facades\Redis;

class PostingUnpublishController extends Controller
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
     * UnPublish the specified resource from storage.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function index(Posting $posting)
    {
        $this->authorize('unpublish', $posting);

        DB::transaction(function () use ($posting) {

            $posting->update([
                'published_date'   => null,
                'published_by'     => null,
            ]);

            PostingTag::where('posting_id', $posting->posting_id)->update(['published_date' => null]);

            Redis::del("postings_" . $posting->posting_id);

            // $posting_filters = PostingFilter::where('posting_id', $posting->posting_id)->get();

            // if( $posting_filters->count() > 0 ){
            //      PostingFilter::where('posting_id', $posting->posting_id)
            //         ->update([
            //             'published_date'   => null,
            //             // 'published_by'     => null,
            //         ]);
            // }

            activity()
                ->performedOn($posting)
                ->withProperties($posting)
                ->log('Posting has been UnPublished');
        });

        return ['success'   => 1];
    }
}
