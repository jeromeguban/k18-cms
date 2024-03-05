<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Posting;
use App\Models\PostingItem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateRetailCartDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:retail-cart-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Cart Retail Details';

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
        $carts = Cart::whereNotNull('posting_item_id')
                        ->get();

        $bar = new ProgressBar($this->output, count($carts));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($carts as $index => $cart) {

            $posting_item = PostingItem::where('id', $cart->posting_item_id)
                            ->first();

            $posting = Posting::where('posting_id', $cart->posting_id)
                            ->first();

            if(!$posting_item)
                continue;
            
            $cart->update([
                'details'                => $posting->toJson(),
                'posting_item_details'   => $posting_item->toJson()
            ]);

            $bar->advance();

        }

        $bar->finish();
    }
}
