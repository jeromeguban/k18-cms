<?php

namespace App\Console\Commands;

use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\OrderPosting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\PostingItem as SearchablePostingItem;

class UpdateItemQuantity extends Command
{
    protected  $order_postings;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:item-quantity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Quantity';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       

        $this->cancel();
       
        $this->comment('Successfully Updated!');
    }


    /**
     * Execute Cancel order process
     *
     * @return void
     */

    public function cancel()
    {
        DB::transaction(function () {
            $this->retrieveOrderPostings()
                ->subtractCheckoutQtyToItemRemainingQty();
        });
    }

    private function subtractCheckoutQtyToItemRemainingQty()
    {

        foreach ($this->order_postings->where('category', 'Retail') as $order_posting) {

            $item = PostingItem::where('posting_id', $order_posting->posting_id)
                ->where('id', $order_posting->posting_item_id)
                ->first();

            $item->update([
                'quantity' => (int) $item->quantity - (int) $order_posting->quantity >= 0 ? (int) $item->quantity - (int) $order_posting->quantity : 0
            ]);

            SearchablePostingItem::where('id', $item->id)
                ->searchable();

            $total_posting_items = PostingItem::select([
                DB::raw('SUM(posting_items.quantity) AS total_quantity')
            ])
                ->where('posting_items.posting_id', $item->posting_id)
                ->groupBy([
                    'posting_items.posting_id'
                ])
                ->first();

            if (!$total_posting_items)
                continue;

            Posting::where('posting_id', $item->posting_id)
                ->update([
                    'quantity' => $total_posting_items->total_quantity
                ]);

            SearchablePosting::where('posting_id', $item->posting_id)
                ->searchable();
        }

        return $this;
    }

    private function retrieveOrderPostings()
    {
        $this->order_postings = OrderPosting::where('category', 'Retail')
            ->whereIn('order_id', [
                20125,
        20145,
        20156,
        20177,
        20215,
        20256,
        20295,
        20343,
        20349,
        20374,
        20386,
        20488,
        20560,
        20576,
        20642,
        20646,
        20664,
        20679,
        20750,
        20777,
        20779,
        20783,
        20831,
        20842,
        21021,
        21052,
        21060,
        21075,
        21082,
        21100,
        21124,
        21193,
        21201,
        21203,
        21244,
        21396,
        21407,
        21411,
        21425,
        21499,
        28933,
        29005,
        29008,
        29011,
        29215,
        29277,
        29298,
        29303,
        29310,
        29452,
        29562,
        29629,
        29677,
        29688,
        29691,
        29701,
        29718,
        29722,
        29737,
        29751,
        29765,
        29783,
        29790,
        29791,
        29792,
        29793,
        29795,
        29796,
        29811,
        29996,
        30003,
        30005,
        30029,
        30036,
        30059,
        30061,
        30063,
        30072,
        30089,
        30111,
        30112,
        30113,
        30117,
        30132,
        30159,
        30264,
        30273,
        30295,
        30341,
        30344,
        30346,
        30348,
        30361,
        30369,
        30377,
        30382,
        30445,
        30483,
        30494,
        30533,
        30557,
        30580,
        30602,
        30625,
        30626,
        30640,
        30658,
        30722,
        30764,
        30766,
        30778,
        30816,
        30825,
        30837,
        30853,
        30858,
        30866,
        30872,
        30890,
        30910,
        30916,
        30917,
        30918,
        30919,
        30920,
        30924,
        30926,
        30929,
        30953,
        31072,
        31131,
        31137,
        31146,
        31165,
        31168,
        31269,
        31346,
        31352,
        31361,
        31362,
        31363,
        31366,
        31367,
        31405,
        31418,
        31485,
        31524,
        31545,
        31563,
        31564,
        31570,
        31573,
        31622,
        31631,
        31635,
        31647,
        31648,
        31669,
        31676,
        31681,
        31723,
        31767,
        31779,
        31805,
        31829,
        31831,
        31838,
        31896,
        31898,
        31937,
        31949,
        31950,
        31951,
        32038
          ])->get();

        return $this;
    }
}
