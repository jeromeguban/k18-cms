<?php

namespace App\Console\Commands;

use App\Models\Auction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMultipleAuctionTalliedBidAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:multiple-auction-tallied-bid-amounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Multiple Auciton Tallied Bid Amounts';

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
        $auctions = Auction::where('ending_time', '<', Carbon::parse('2022-04-29 12:00:00')->toDateTimeString())
                        ->get();



        $auctions->each(function($auction) {
            $this->call('generate:tallied-bid-amounts', ['auction'=>$auction->auction_id]);
        });
    }
}
