<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posting;
use App\Jobs\Auction\AuctionPostingJob;

class GenerateAuctionZeroBidAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-zero-amount {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Amount for Zero Amount';

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
        $postings = Posting::where('postings.auction_id', $this->argument('auction'))
                        ->whereNull('postings.deleted_at')
                        ->get();

        foreach($postings as $posting) {
            AuctionPostingJob::dispatchSync($posting);
        }
    }
}
