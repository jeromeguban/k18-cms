<?php

namespace App\Console\Commands;

use App\Helpers\CacheHelper;
use App\Models\Auction;
use App\Models\Posting;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class GeneratePostingCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:posting-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Published Posting Cache';

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

        $auctions = Auction::where('ending_time', '>', now()->toDateTimeString())
                            ->whereNotNull('published_date')
                            ->get();
        
        $auction_bar = new ProgressBar($this->output, count($auctions));

        $auction_bar->setFormat("Processing auctions: %current%/%max% [%bar%] %remaining%");

        $auction_bar->start();      

        foreach($auctions as $auction) {

            $postings = Posting::wherePublished()
                            ->where('auction_id', $auction->auction_id)
                            ->orderByRaw("CHAR_LENGTH(lot_number) ASC, lot_number ASC")
                            ->where(function($query) {
                                $query->whereNotYetFinished();       
                            })
                            ->get();



            $postings->each(function($posting){
                (new CacheHelper)->setModelCache($posting);
            });


            $auction_bar->advance();
        }

        $auction_bar->finish();
        print "\n";
        $this->comment('Posting Cache successfully generated!');
    }
}
