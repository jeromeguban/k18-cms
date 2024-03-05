<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posting;
use App\Models\OrderPosting;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateAuctionOrderPostingDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction-order-posting-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Auction Order Posting Details';

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
        $order_postings = OrderPosting::whereNull('details')
                                    ->where('category', '=', 'Auction')
                                    ->get();
 
        $posting_ids = $order_postings->pluck('posting_id')
                                ->toArray();

        $postings = Posting::whereIn('posting_id', $posting_ids)
                        // ->whereNotNull('published_date')
                        ->get();

        $bar = new ProgressBar($this->output, count($order_postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($order_postings as $index => $order_posting) {

            $posting = $postings->where('posting_id', $order_posting->posting_id)
                            ->first();
            if(!$posting)
                continue;
            
            $order_posting->update([
                'details'   => $posting->toJson()
            ]);

            $bar->advance();

        }

        $bar->finish();
    }
}
