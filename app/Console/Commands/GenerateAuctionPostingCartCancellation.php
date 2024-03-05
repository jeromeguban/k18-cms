<?php

namespace App\Console\Commands;

use App\Models\Posting;
use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Auction\PostingCancellationJob;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateAuctionPostingCartCancellation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-posting-cart-cancellation';

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

        $posting_carts = Posting::select(['postings.posting_id','postings.payment_period'])
                            ->join('carts','carts.posting_id','=','postings.posting_id')
                            ->whereNull('postings.cancelled_date')
                            ->whereNotNull('postings.finalized_date')
                            ->where('postings.category','Auction')
                            ->whereNotNull('postings.auction_id')
                            ->where('postings.ending_time', '<' ,now()->toDateTimeString())
                            ->whereRaw('postings.payment_period + interval 2 day ', '<' ,now()->toDateTimeString())
                            ->where('carts.expires_at', '<' ,now()->toDateTimeString())
                            ->get();
        
        $bar = new ProgressBar($this->output, count($posting_carts));

        $bar->setFormat("Processing Posting Carts: %current%/%max% [%bar%] %remaining%");

        $bar->start();      

        foreach($posting_carts as $posting_cart) {

            $posting = Posting::where('posting_id', $posting_cart->posting_id)->first();
            $cart    = Cart::where('posting_id', $posting_cart->posting_id)->first();

            \DB::transaction(function() use($posting, $cart){

                dispatch(new PostingCancellationJob($posting));

                $posting->update(['cancelled_date' => now()->toDateTimeString()]);
                $cart->delete();

            });

            $bar->advance();
        }

        $bar->finish();
        print "\n";

        $this->comment('Postings Successfully Cancelled!');
    }
}
