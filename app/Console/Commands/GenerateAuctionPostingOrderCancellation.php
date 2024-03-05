<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Posting;
use App\Models\OrderPosting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Auction\PostingCancellationJob;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateAuctionPostingOrderCancellation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-posting-order-cancellation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auction Posting Cancellation';

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

        $order_items = Posting::select(['postings.posting_id','postings.payment_period','order_postings.order_id'])
                            ->join('order_postings','order_postings.posting_id','=','postings.posting_id')
                            ->join('orders','orders.id','=','order_postings.order_id')
                            ->whereNotNull('postings.finalized_date')
                            ->where('postings.category','Auction')
                            ->whereNotNull('postings.auction_id')
                            ->where('postings.ending_time', '<' ,now()->toDateTimeString())
                            ->whereRaw('postings.payment_period + interval 2 day ', '<' ,now()->toDateTimeString())
                            ->whereNull('postings.cancelled_date')
                            ->whereNull('order_postings.cancelled_date')
                            ->whereNull('orders.payment_id')
                            ->where('orders.order_status', 'Pending')
                            ->where('orders.payment_status', 'Pending')
                            ->get();

        $bar = new ProgressBar($this->output, count($order_items));

        $bar->setFormat("Processing Order Postings: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($order_items as $order_item) {

            $posting        = Posting::where('posting_id', $order_item->posting_id)->first();
            $order_posting  = OrderPosting::where('posting_id', $order_item->posting_id)->first();
            try {
                    \DB::transaction(function() use($posting, $order_posting){

                    

                            dispatch(new PostingCancellationJob($posting));

                            $posting->update(['cancelled_date' => now()->toDateTimeString()]);

                            if($order_posting)
                            $order_posting->update([
                                'cancelled_date' => now()->toDateTimeString(),
                            ]);

                            $this->validateOrderItemCount($order_posting);

                    });
            } catch (\Exception $e) {

            }

            $bar->advance();
        }

        $bar->finish();
        print "\n";

        $this->comment('Postings Successfully Cancelled!');
    }


    private function validateOrderItemCount($order_posting) {


        $order = Order::where('id', $order_posting->order_id)->first();

        $order_postings = OrderPosting::where('order_id', $order_posting->order_id)
                            ->whereNull('cancelled_date')
                            ->get();

        if(!$order_postings->count())
            $order->update(['cancelled_date' => now()->toDateTimeString()]);

    }
}
