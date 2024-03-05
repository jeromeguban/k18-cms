<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Searchable\Auction as SearchableAuction;
use App\Models\BidderDeposit;
use App\Models\Hammer\BidderDeposit as HammerBidderDeposit;

class FixEeiBidDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:auction-eei-bid-deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix EEI Event';

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
        $auctions = Auction::whereIn('auction_id', [7002,7001,6992,7013])
                        ->get();

        foreach($auctions as $auction) {
            $auction->update([
                'exclusive_bid_deposit' => 0
            ]);

            SearchableAuction::where('auction_id', $auction->auction_id)
                ->searchable();

            $cms_bidder_deposits = BidderDeposit::where('bidder_deposits.auction_id', $auction->auction_id)
                                            ->get();

            foreach($cms_bidder_deposits as $cms_bidder_deposit) {
                $cms_bidder_deposit->update([
                    'auction_id'    => null
                ]);
                
                continue;
            }
            
        }
    }
}
