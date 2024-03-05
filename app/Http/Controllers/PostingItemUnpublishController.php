<?php

namespace App\Http\Controllers;

use App\Models\Posting;
use App\Models\PostingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PostingUnpublishController;

class PostingItemUnpublishController extends Controller
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
     * @param  \App\Models\PostingItem  $posting
     * @return \Illuminate\Http\Response
     */
    public function index(PostingItem $posting_item)
    {
        $this->authorize('unpublish', $posting_item);

        DB::transaction(function () use ($posting_item) {

            $posting_item->update([
                'published_date'   => null,
                'published_by'     => null,
            ]);

            $total_published_items = PostingItem::where('posting_id', $posting_item->posting_id)
                                            ->whereNotNull('published_date')
                                            ->count();

            if (!$total_published_items) {

                $posting = Posting::where('posting_id', $posting_item->posting_id)->first();

                try {

                    $posting_unpublish_controller = new PostingUnpublishController();
                    $posting_unpublish_controller->index($posting);

                } catch (\Exception $exception) {

                }


            }

            activity()
                ->performedOn($posting_item)
                ->withProperties($posting_item)
                ->log('Posting Item has been UnPublished');
        });

        return ['success'   => 1];
    }
}
