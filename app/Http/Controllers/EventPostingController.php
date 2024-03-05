<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Posting;
use App\Models\UserStore;
use App\Models\PostingItem;
use Illuminate\Http\Request;
use App\Models\Searchable\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\PostingItem as SearchablePostingItem;

class EventPostingController extends Controller
{
	public function index(Event $event)
    {
        $this->authorize('list', Posting::class);


        $query = null;

        $query = SearchablePosting::elastic()
                    ->where('event_id', $event->event_id)
                    ->where('store_id', $event->store_id)
                    ->where('quantity', '>', 0)
                    ->limit(1000)
                    ->orderBy('sequence', 'ASC');


        $response = $this->response($query);

        $posting_ids = (request()->action == 'pagination' ? collect($response->items()) : $response)->filter(function ($product) {
            return $product->category == 'Retail';
        })->pluck('posting_id')
            ->values()
            ->toArray();

        $product_items = SearchablePostingItem::elastic()
            ->whereIn('posting_id', $posting_ids)
            ->where('store_id', $event->store_id)
            ->limit(1000)
            ->get();

        $store_ids = $product_items->pluck('store_id')
            ->unique()
            ->flatten()
            ->filter(function ($store) {
                return ! is_null($store);
            })
            ->values()
            ->toArray();

        $stores = Store::elastic()
            ->search('*')
            ->whereIn('id', $store_ids)
            ->limit(1000)
            ->get();

        $product_items->each(function ($item) use ($stores) {
            $store = $stores->where('id', $item->store_id)->first();
            if ($store) {
                $item->store_name = $store->store_name;
            }

            $posting_cart_keys = Redis::connection('hmr-local-redis')->keys("item:{$item->id}:cart:*");
            $posting_cart_cache = count($posting_cart_keys) ? Redis::connection('hmr-local-redis')->mget($posting_cart_keys) : [];

            $total_quantity_in_cart = collect($posting_cart_cache)->sum() ?? 0;

            $item->quantity = $item->quantity > 0 ? ($item->quantity - $total_quantity_in_cart) : 0;

        });

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($posting) use ($product_items) {

            $posting->items = $product_items->filter(function ($item) use ($posting) {
                return $item->posting_id == $posting->posting_id;
            })->values()
                ->map(function ($item) {
                    $item->images = is_array($item->images) ? $item->images : json_decode($item->images);

                    return $item;
                });

            if ($posting->attribute_data) {
                $posting->attribute_data = json_decode($posting->attribute_data);
            }

            $posting->images = is_array($posting->images) ? $posting->images : json_decode($posting->images);


            // $store = Store::where('id', $posting->store_id)->first();
            // $posting->store = $store ? $store : null;

        });

        return $response;
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posting = Posting::where('posting_id', $request->posting_id)->first();
        $event = Event::where('event_id', $request->event_id)->first();

        $request->validate([
                'posting_id' => 'required',
                'event_id'   => 'required',
            ],
            [
                'posting_id'     => 'Item',
                'event_id'       => 'Event',
            ]);

        DB::transaction(function() use($posting, $event, $request){

            $posting->update([
                'event_id' => $request->event_id,
                'event_holded_at' => $event->holded_at ?? null,
                'sequence' =>  Posting::getLastestPostingSequence($request->event_id) ?? 0,
                'term_id' =>  $event->term_id ? $event->term_id : null
            ]);


            $posting_items = PostingItem::where('posting_id', $posting->posting_id)
                                ->get();

            foreach($posting_items as $index => $posting_item) {
                Redis::set("postings_".$posting_item->posting_id."_items_".$posting_item->id, $posting_item->toJson());
            }

            $posting->items = $posting_items->toArray();

            Redis::set("postings_".$posting->posting_id, $posting->toJson());

            SearchablePosting::where('posting_id', $posting->posting_id)
                ->searchable();

            SearchablePostingItem::where('posting_id', $posting->posting_id)
                ->searchable();



        });

        activity()
            ->performedOn($posting)
            ->withProperties($posting)
            ->log('Posting has been added on Event');

        return [
            'success'   => 1,
            'data'      => $posting
        ];
    }

    public function remove(Posting $posting)
    {
        $posting = Posting::find($posting->posting_id);

        DB::transaction(function() use($posting){

            if ($posting) {
                $posting->update([
                    'event_id'  => null,
                    'event_holded_at' => null,
                    'term_id' =>  null
                ]);
            }

            SearchablePosting::where('posting_id', $posting->posting_id)->searchable();

            Redis::delete("postings_".$posting->posting_id);

            $posting_items = PostingItem::where('posting_id', $posting->posting_id)->get();

            foreach($posting_items as $index => $posting_item) {
                Redis::delete("postings_".$posting_item->posting_id."_items_".$posting_item->id);
            }

        });

        activity()
            ->performedOn($posting->refresh())
            ->withProperties($posting->refresh())
            ->log('Posting has been Removed to Event');

        return [
            'success'   => 1
        ];
    }
}
