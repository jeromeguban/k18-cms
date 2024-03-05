<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Helpers\CacheHelper;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateRetailPostingCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:retail-posting-cache';

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

        $postings = Posting::wherePublished()
                        ->get();
        
        $auction_bar = new ProgressBar($this->output, count($postings));

        $auction_bar->setFormat("Processing auctions: %current%/%max% [%bar%] %remaining%");

        $auction_bar->start();      

        foreach($postings as $posting) {

            (new CacheHelper)->setModelCache($posting);

            $auction_bar->advance();
        }

        $auction_bar->finish();
        print "\n";
        $this->comment('Posting Cache successfully generated!');
    }
}
