<?php

namespace App\Console\Commands;

use App\Helpers\CacheHelper;
use App\Models\Auction;
use App\Models\Posting;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Support\Facades\Redis;

class GenerateAuctionPostingAskingBid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-posting-asking-bid {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Published Auction Posting Asking Bid';

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

        $auction = Auction::where('auction_id', $this->argument('auction'))
                            ->whereNotNull('published_date')
                            ->first();

    
        $postings = Posting::wherePublished()
                        ->where('auction_id', $auction->auction_id)
                        ->whereNull('bid_amount')
                        ->orderByRaw("CHAR_LENGTH(lot_number) ASC, lot_number ASC")
                        ->get();
                        
        $postings->each(function($posting){
      
             Redis::set("postings_".$posting->posting_id."_asking_bid", $posting->starting_amount);
        });

        $this->comment('Posting Asking Bid successfully generated!');
    }


}
