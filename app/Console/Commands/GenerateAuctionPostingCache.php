<?php

namespace App\Console\Commands;

use App\Helpers\CacheHelper;
use App\Models\Auction;
use App\Models\Posting;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateAuctionPostingCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-posting-cache {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Published Auction Posting Cache';

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
        $auction = null;
        if($this->argument('auction') != 'ALL'){

            $auction = Auction::where('auction_id', $this->argument('auction'))
                ->whereNotNull('published_date')
                ->first();

            (new CacheHelper)->setModelCache($auction);
        }

        if($this->argument('auction') == 'ALL'){

            Auction::whereNotNull('published_date')
                ->where('ending_time', '>', now()->toDateTimeString())
                ->get()
                ->each(function($auction) {
                    (new CacheHelper)->setModelCache($auction);
                });

        }

        $postings = Posting::wherePublished()
                        ->when($auction, function($query) use ($auction) {
                            $query->where('auction_id', $auction->auction_id);
                        })
                        ->when(!$auction, function($query) {
                            $query->where('ending_time', '>', now()->toDateTimeString());
                        })
                        ->orderByRaw("CHAR_LENGTH(lot_number) ASC, lot_number ASC")
                        // ->where(function($query) {
                        //     $query->whereNotYetFinished();
                        // })
                        ->get();

        $postings->each(function($posting){
            (new CacheHelper)->setModelCache($posting);
        });

        $this->comment('Posting Cache successfully generated!');
    }
}
