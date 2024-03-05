<?php

namespace App\Console\Commands;

use App\Helpers\CacheHelper;
use App\Models\BidHistory;
use App\Models\MaxBidHistory;
use App\Models\Posting;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

use Illuminate\Support\Facades\Redis;

class GenerateWinningBidCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:winning-bid {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Winning Bid Cache';

    protected $posting_bar;
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
     * @return int
     */
    public function handle()
    {
        $postings = Posting::where('category', 'Auction')
                        // ->whereBetween('ending_time', ['2022-06-17 00:00:00', '2022-06-17 23:59:59'])
                        ->where('auction_id', $this->argument('auction'))
                        ->get();

        $this->posting_bar = new ProgressBar($this->output, count($postings));

        $this->posting_bar->setFormat("Processing postings: %current%/%max% [%bar%] %remaining%");

        $this->posting_bar->start();

        $postings->each(function($posting) {
            (new CacheHelper)->setModelCache($posting);
            try {

                $bid_history = BidHistory::where('posting_id', $posting->posting_id)
                                    ->orderBy('bid_amount', 'DESC')
                                    ->first();


                $bid_history = $bid_history ?? $posting;

                $max_bid_histories = MaxBidHistory::where('posting_id', $posting->posting_id)
                    ->get()
                    ->toJson();

                $asking_bid = (float) $bid_history->bid_amount + $this->getIncrementAmount($posting, $bid_history);

                if(!$bid_history->bid_amount){
                    Redis::delete("postings_".$posting->posting_id."_asking_bid");
                }
                if($bid_history->bid_amount){
                    Redis::set("postings_".$posting->posting_id."_bidder_number_id", $bid_history->bidder_number_id);
                    Redis::set("postings_".$posting->posting_id."_customer_id", $bid_history->customer_id);
                    Redis::set("postings_".$posting->posting_id."_bid_amount", $bid_history->bid_amount);
                    Redis::set("postings_".$posting->posting_id."_asking_bid", $asking_bid);
                    Redis::set("postings_".$posting->posting_id."_max_bids", $max_bid_histories);
                }
                $this->posting_bar->advance();

            } catch (\Exception $e) {

            }
        });

        $this->posting_bar->finish();



    }

    private function getIncrementAmount($posting, $bid_history) {
        if($posting->increment_type == 'starting_amount')
            return (float)$posting->starting_amount * (float)($posting->increment_percentage/100);

        if($posting->increment_type == 'asking_bid')
            return (float)$bid_history->bid_amount * (float)($posting->increment_percentage/100);


        if($posting->increment_type == 'increment_table') {

            $increment_table = json_decode(Redis::get('auctions_'.$posting->auction_id.'_increment_table'));

            if(!$increment_table) {
                if( (float) $bid_history->bid_amount >= 49 && (float) $bid_history->bid_amount <= 999 )
                    return 50;

                if( (float) $bid_history->bid_amount >= 1000 && (float) $bid_history->bid_amount <= 9999 )
                    return 500;

                if( (float) $bid_history->bid_amount >= 10000 && (float) $bid_history->bid_amount <= 19999 )
                    return 1000;

                if( (float) $bid_history->bid_amount >= 20000 && (float) $bid_history->bid_amount <= 49999 )
                    return 2000;

                if( (float) $bid_history->bid_amount >= 50000 && (float) $bid_history->bid_amount <= 199999 )
                    return 5000;

                if( (float) $bid_history->bid_amount >= 200000 && (float) $bid_history->bid_amount <= 599999 )
                    return 10000;

                if( (float) $bid_history->bid_amount >= 600000 && (float) $bid_history->bid_amount <= 1000000 )
                    return 10000;

                if( (float) $bid_history->bid_amount >= 1000000 && (float) $bid_history->bid_amount <= 4999999 )
                    return 50000;

                if( (float) $bid_history->bid_amount >= 5000000 )
                    return 100000;
            }

            foreach( $increment_table as $increment) {
				if($increment->minimum && $increment->maximum
					&& (float)$bid_history->bid_amount >= (float)$increment->minimum
					&& (float)$bid_history->bid_amount <= (float)$increment->maximum ) {
					return (float)$increment->increment;
				}

				if($increment->minimum && !$increment->maximum
					&& (float)$bid_history->bid_amount >= (float)$increment->minimum ) {
					return (float)$increment->increment;
				}
			}


        }
    }
}
