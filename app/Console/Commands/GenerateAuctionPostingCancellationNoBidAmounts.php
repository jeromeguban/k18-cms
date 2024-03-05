<?php

namespace App\Console\Commands;

use App\Models\Posting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Auction\PostingCancellationJob;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateAuctionPostingCancellationNoBidAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-posting-cancellation-no-bid-amounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auction Posting Cancellation';

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

        $postings = Posting::selectedFields()
                        ->whereNull('postings.bid_amount')
                        ->whereNotNull('postings.finalized_date')
                        ->where('postings.category','Auction')
                        ->whereNotNull('postings.auction_id')
                        ->where('postings.ending_time', '<' ,now()->toDateTimeString())
                        ->where('postings.payment_period', '<' ,now()->toDateTimeString())
                        ->whereNull('postings.cancelled_date')
                        ->get();
        
        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing Postings: %current%/%max% [%bar%] %remaining%");

        $bar->start();      

        foreach($postings as $posting) {

           \DB::transaction(function() use($posting){

               dispatch(new PostingCancellationJob($posting));

               $posting->update(['cancelled_date' => now()->toDateTimeString()]);

            });

            $bar->advance();
        }

        $bar->finish();
        print "\n";

        $this->comment('Postings Successfully Cancelled!');
    }
}
