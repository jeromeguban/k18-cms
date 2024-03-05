<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Posting;
use App\Models\Auction;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class UpdateCartDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cart-details {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Cart Details';

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

        $auction = Auction::where('auction_id', $this->argument('auction'))
                        ->firstOrFail();

        $postings = Posting::where('auction_id', $auction->auction_id)
                        ->whereNotNull('published_date')
                        ->get();

        $carts = Cart::whereIn('posting_id', $postings->pluck('posting_id')->toArray())->get();

        $bar = new ProgressBar($this->output, count($carts));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($carts as $index => $cart) {

            $posting = $postings->where('posting_id', $cart->posting_id)
                            ->first();
            if(!$posting)
                continue;

            $cart->update([
                'details'   => $posting->toJson(),
                'price'     => $this->getTotalAmount($posting->refresh()),
            ]);

            $bar->advance();

        }

        $bar->finish();
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
