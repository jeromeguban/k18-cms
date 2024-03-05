<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostingRequest;
use App\Models\Posting;
use App\Models\PostingFilter;
use App\Models\Store;
use App\Models\UserStore;
use App\Processes\PostingProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostingController extends Controller
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
        $this->authorize('list', Posting::class);

        $query = Posting::withRelations()
            ->whereQueryScopes()
            ->searchable();

        if (!request()->category && !request()->store) {

            // Select All Data if user has access on all Stores
            // $all_store = $this->authorize('all', Store::class) ? true : false;

            // if (!$all_store) {

            //     $query->where('postings.store_id', session()->get('store_id'));

            // } else {
            //     $store = Store::selectedFields()
            //         ->pluck('id')
            //         ->toArray();

            //     $query->whereIn('postings.store_id', $store);
            // }

            $query->where('postings.store_id', session()->get('store_id'));

        }

        if (request()->category && request()->store == 'all') {

            // Select All Data if user has access on all Stores
            // $all_store = $this->authorize('all', Store::class) ? true : false;

            // if (!$all_store) {

            //     $store = UserStore::where('user_id', auth()->id())
            //         ->get()
            //         ->pluck('store_id')
            //         ->toArray();

            //     $query->whereIn('postings.store_id', $store);

            // } else {
            //     $store = Store::selectedFields()
            //         ->pluck('id')
            //         ->toArray();

            //     $query->whereIn('postings.store_id', $store);
            // }
            $store = UserStore::where('user_id', auth()->id())
                ->get()
                ->pluck('store_id')
                ->toArray();

            $query->whereIn('postings.store_id', $store);

        }

        if (request()->category == 'Retail' && !request()->store && request()->store != 'all') {

            $query->whereNull('auction_id');
            $query->where('postings.store_id', session()->get('store_id'));
        }

        if (request()->category == 'Retail' && request()->store && request()->store != 'all') {

            $query->whereNull('auction_id');
            $query->where('postings.store_id', request()->store);
        }

        if (request()->category == 'Auction' && !request()->store && request()->store != 'all') {

            $query->whereNotNull('auction_id');
            $query->where('postings.store_id', session()->get('store_id'));
        }

        if (request()->category == 'Auction' && request()->store && request()->store != 'all') {

            $query->whereNotNull('auction_id');
            $query->where('postings.store_id', request()->store);
        }

        if (request()->category == 'Whole Sale' && !request()->store && request()->store != 'all') {

            $query->where('postings.category', 'Wholesale');

        }

        if (request()->category == 'Expression of Interest' && !request()->store) {

            $query->where('postings.category', 'Expression of Interest');

        }

        if (request()->category == 'International Trade' && !request()->store) {

            $query->where('postings.category', 'International Trade');

        }

        if (request()->category == 'Whole Sale' && !request()->store) {

            $query->where('postings.category', 'Wholesale');

        }

        if (request()->order_type) {
            $query->orderBy('postings.' . request()->filter_type, request()->order_type);
        }

        $query->sortable();

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostingRequest $request, PostingProcess $process)
    {
        $this->authorize('create', Posting::class);

        $process->create();

        activity()
            ->performedOn($process->posting())
            ->withProperties($process->posting())
            ->log('Posting has been created');

        return [
            'success' => 1,
            'data' => $process->posting(),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(PostingRequest $request, PostingProcess $process, Posting $posting)
    {
        $this->authorize('update', $posting);

        $process->find($posting->posting_id)
            ->update();

        activity()
            ->performedOn($process->posting())
            ->withProperties($process->posting())
            ->log('Module has been updated');

        return [
            'success' => 1,
            'data' => $process->posting(),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function show(Posting $posting)
    {
        $this->authorize('view', $posting);

        $posting = Posting::where('posting_id', $posting->posting_id)
            ->withRelations()
            ->first();

        return $posting;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posting $posting)
    {
        $this->authorize('delete', $posting);

        DB::transaction(function () use ($posting) {

            $posting_filters = PostingFilter::where('posting_id', $posting->posting_id)->get();

            if ($posting_filters->count() > 0) {
                PostingFilter::where('posting_id', $posting->posting_id)->delete();
            }

            $posting->update([
                'deleted_by' => auth()->id(),
            ]);

            $posting->delete();
            activity()
                ->performedOn($posting)
                ->withProperties($posting)
                ->log('Posting has been deleted');
        });

        return ['success' => 1];
    }

}
