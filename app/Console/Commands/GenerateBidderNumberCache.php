<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\BidderNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateBidderNumberCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:bidder-number-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Bidder Number Cache';

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

        $auctions = Auction::where('ending_time', '>', now()->toDateTimeString())
                    ->where('starting_time', '<', now()->toDateTimeString())
                    ->whereNotNull('published_date')
                    ->get()
                    ->pluck('auction_id')
                    ->toArray();

        $bidder_numbers = BidderNumber::whereIn('auction_id',$auctions)->get();

        $bar = new ProgressBar($this->output, count($bidder_numbers));

        $bar->setFormat("Processing Bidder Numbers: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($bidder_numbers as $bidder_number) {
            Redis::set("bidder_number_".$bidder_number->bidder_number_id, $bidder_number->bidder_number);
            Redis::set("auction_".$bidder_number->auction_id."_customer_".$bidder_number->customer_id, $bidder_number->bidder_number_id);
            $bar->advance();
        }

        $bar->finish();
        print "\n";

        $this->comment('Bidder Number Successfully Generated!');
    }
}
