<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Auction;
use App\Models\OrderPosting;
use App\Models\Posting;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;


class ValidateIfLotIsNotForApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:posting-for-approval {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate if Auction Posting is Not For Approval';

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
        $postings   = Posting::whereNotNull('postings.published_date')
                        ->join('auctions','auctions.auction_id','=','postings.auction_id')
                        ->where('postings.category', 'Auction')
                        ->where('postings.bid_amount', '>', 'postings.reserved_price')
                        ->whereNotNull('postings.finalized_date')
                        ->where('auctions.for_approval', 0)
                        ->where('postings.for_approval', 1)
                        ->whereNull('postings.approved_by')
                        ->when($this->argument('auction') == 'ALL', function($query) {
                            $query->where('postings.ending_time', '<', now()->toDateTimeString());
                        }, function($query){
                            $query->where('postings.auction_id', $this->argument('auction'));
                        })
                        ->get();


        if(!$postings->count())
            return $this->comment('No Postings needed to be tallied!');

        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing Postings: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($postings as $posting) {

           $cart = Cart::where('posting_id', $posting->posting_id)->first();
           $order = OrderPosting::where('posting_id', $posting->posting_id)->first();

           if(!$cart && !$order){

             Posting::whereNotNull('postings.published_date')
                ->where('postings.posting_id', $posting->posting_id)
                ->where('postings.category','Auction')
                ->update([
                    'finalized_date' => null,
                    'for_approval' => 0,
                ]);

           }else{
               continue;
           }

           $bar->advance();

        }

        $bar->finish();
        print "\n";

        $this->comment('Posting Successfully Validated!');

    }
}
