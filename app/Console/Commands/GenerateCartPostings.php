<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Posting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateCartPostings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:cart-postings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Winning Bids to Cart';

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

        $postings = Posting::whereNull('ordered_date')
                            ->whereNotNull('bid_amount')
                            ->whereNotNull('customer_id')
                            ->whereNotNull('finalized_date')
                            ->whereNotNull('approved_date')
                            ->where('category', 'Auction')
                            ->get();

        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();   
        foreach($postings as $posting)
        try {

            \DB::transaction(function()use($posting){

                 Cart::updateOrCreate([
                    'customer_id'       => $posting->customer_id,
                    'posting_id'        => $posting->posting_id,
                    'store_id'          => $posting->store_id,
                    'posting_item_id'   => null,
                ],[
                    'price'         => $this->getTotalAmount($posting->refresh()),
                    'quantity'      =>  1,
                    'expires_at'    => $posting->payment_period ? Carbon::parse($posting->payment_period)->toDateTimeString() : Carbon::parse($posting->ending_time)->addDays(5)->toDateTimeString(),
                    // 'details'       => json_encode($posting),
                    'category'      => 'Auction'
                ]);
               
                
            });

            $bar->advance();
        } catch (\Exception $e) {
                    
        }

        $bar->finish();
        print "\n";   
    }


    private function getTotalAmount($posting)
    {
        return  (float) $posting->bid_amount + 
                (float) $posting->processing_fee +
                (float) $posting->notarial_fee +
                (float) $posting->other_fee +
                ((float)$posting->bid_amount * (float)($posting->buyers_premium /100)) + 
                ((float)$posting->bid_amount * (float)($posting->vat /100)) + 
                ((float)$posting->bid_amount * (float)($posting->duties /100)); 
    }
}
