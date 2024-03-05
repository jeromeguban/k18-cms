<?php

namespace App\Console\Commands;

use App\Models\BidHistory;
use App\Models\Posting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class GenerateTalliedBidHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tallied-bid-history {posting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Tallied Bid History';

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
        try{

            $posting = Posting::findOrFail($this->argument('posting'));
            $bid_histories = Redis::get("postings_".$posting->posting_id."_bid_histories");

            $bid_histories = json_decode($bid_histories,true);

            foreach ($bid_histories as $bid_history) {
                BidHistory::updateOrCreate([ 
                    'bid_amount'        => (float)$bid_history['bid_amount'],
                    'posting_id'        => $posting->posting_id, 
                ],[
                    'bidder_number_id'  => $bid_history['bidder_number_id'], 
                    'customer_id'       => $bid_history['customer_id'], 
                    'server_time'       => $bid_history['server_time'],
                    'max_bid'           => $bid_history['max_bid'],
                ]);
            }


        } catch (\Exception $e) {
            echo $e->getMessage();
        } 

    }
}
