<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use App\Models\Posting;
use App\Jobs\ItemBidderNumberJob;

class GenerateBidderNumberITems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:item-bidder-number-id {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $postings = Posting::selectedFields()
                            ->whereNull('deleted_at')
                            ->where('auction_id', $this->argument('auction'))
                            ->get();

        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($postings as $posting){
            ItemBidderNumberJob::dispatch($posting);

            $bar->advance();
        }

        $bar->finish();
            print "\n";
            $this->comment('Successfully Generated');
    }
}
