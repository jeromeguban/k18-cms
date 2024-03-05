<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\UserStore;
use Illuminate\Http\Request;

class EventPostingItemSkuController extends Controller
{
	public function index(PostingItem $posting_item)
    {
        $this->authorize('list', Posting::class);
        
        $posting_item = PostingItem::where('posting_items.store_id', session()->get('store_id'))
            ->where('posting_items.id', $posting_item->id)
            ->first();
           
      
        if(!$posting_item){
            abort(403, "Invalid Barcode");
        }

        if($posting_item->quantity == 0){
            abort(403, "Zero Quantity");
        }

        $posting = Posting::where('posting_id',  $posting_item->posting_id)
            ->where('postings.store_id', session()->get('store_id'))
            ->first();
        
        if($posting->event_id){
            $event = Event::where('event_id', $posting->event_id)->first();
            abort(403, "Already Added on Event"." - ".$event->event_name);
        }

        if(!$posting){
            abort(403, "Invalid Barcode");
        }

        return $posting;
    }
}
