<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Jobs\ZeroOrderPostingJob;
use App\Models\Posting;
use App\Models\OrderPosting;

class GenerateAuctionOrderZeroAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-order-zero-amount {order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Auction Order Zero Amount';

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
        $order_postings = OrderPosting::selectedFields()
                    ->where('order_postings.order_id', $this->argument('order'))
                    ->get();

        $order_posting_ids = $order_postings->pluck('posting_id')->toArray();

        $postings = Posting::whereIn('postings.posting_id', $order_posting_ids)
                        ->get();

        foreach($postings as $posting){
            ZeroOrderPostingJob::dispatchSync($posting);
        }


        
    }
}
