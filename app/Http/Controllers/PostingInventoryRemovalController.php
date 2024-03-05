<?php

namespace App\Http\Controllers;

use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\PostingTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PostingInventoryRemovalController extends Controller
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
        $this->authorize('inventory-removal', $posting);

        DB::transaction(function () use ($posting) {

            $posting->update([
                'published_date' => null,
                'published_by' => null,
            ]);

            PostingTag::where('posting_id', $posting->posting_id)->update(['published_date' => null]);

            Redis::del("postings_" . $posting->posting_id);

            $posting_items = PostingItem::where('posting_id', $posting->posting_id)->get();

            if ($posting_items->count() > 0) {
                PostingItem::where('posting_id', $posting->posting_id)
                    ->update([
                        'published_date' => null,
                        'published_by' => null,
                    ]);
            }

            activity()
                ->performedOn($posting)
                ->withProperties($posting)
                ->log('Posting has been UnPublished Using Zero Out Inventory');
        });

        return ['success' => 1];
    }
}
