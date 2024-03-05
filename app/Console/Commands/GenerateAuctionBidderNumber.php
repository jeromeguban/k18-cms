<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BidderNumber;
use App\Models\Auction;
use App\Jobs\Auction\AuctionBidderNumberJob;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateAuctionBidderNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-bidder-number {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Auction Bidder Number';

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
        // $auction = Auction::whereBetween('auctions.starting_time', ["2022-05-01 00:00:00", "2022-06-15 23:59:59"])
        //                 ->get();

        $bidder_numbers = BidderNumber::where('auction_id', $this->argument('auction'))
                                    ->whereNull('deleted_at')
                                    ->get();

        $bar = new ProgressBar($this->output, count($bidder_numbers));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();


        foreach($bidder_numbers as $bidder_number){
            // dispatch(new AuctionBidderNumberJob($bidder_number));
            AuctionBidderNumberJob::dispatch($bidder_number);
                                
            $bar->advance();
        }

        $bar->finish();
            print "\n";
            $this->comment('Bidder Number Successfully Generated');
    }

}
