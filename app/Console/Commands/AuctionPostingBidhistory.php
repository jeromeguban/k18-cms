<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Auction;
use App\Models\Posting;
use App\Models\BidHistory;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Helpers\GuzzleRequest;
use App\Jobs\CheckIfBuyBackJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Auction\FinalizeWinningBidJob;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Console\Helper\ProgressBar;

class AuctionPostingBidhistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:bid-history {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate BidHistory to a specific Auction.';

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
       
        $postings   = Posting::whereNotNull('published_date')
                        ->where('category', 'Auction')
                        ->where('auction_id', $this->argument('auction'))
                        ->whereNotNull('finalized_date')
                        ->get();
    

        if(!$postings->count())
            return $this->comment('No Postings needed to be tallied!');
         
        $this->posting_bar = new ProgressBar($this->output, count($postings));

        $this->posting_bar->setFormat("Processing postings: %current%/%max% [%bar%] %remaining%");

        $this->posting_bar->start();

        $postings->each(function($posting) {

            \DB::transaction(function() use ($posting) {
                try {

                    // $this->generateBidHistory($posting->refresh());

                    \Artisan::call('generate:tallied-bid-history', ['posting' => $posting->posting_id, 'posting' => $posting->posting_id]);

                   
                } catch (\Exception $e) {
                    echo $e->getMessage();
                } 
            });

            $this->posting_bar->advance();
            
        });

        $this->posting_bar->finish();
        print "\n";

        return $this->comment('Bidhistory successfuly Generated!');
    }

   
    private function generateBidHistory($posting)
    {
        $bid_histories = Redis::get("postings_".$posting->posting_id."_bid_histories");

        $bid_histories = json_decode($bid_histories,true);

        foreach ($bid_histories as $bid_history) {
            BidHistory::updateOrCreate([ 
                'bid_amount'        => $bid_history['bid_amount'],
                'posting_id'        => $posting->posting_id, 
            ],[
                'bidder_number_id'  => $bid_history['bidder_number_id'], 
                'customer_id'       => $bid_history['customer_id'], 
                'server_time'       => $bid_history['server_time'],
                'max_bid'           => $bid_history['max_bid'],
            ]);
        }
    }

}
