<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Posting;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateCartDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:cart-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Cart Details';

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
        $carts = Cart::whereNull('details')
                        ->get();

        $posting_ids = $carts->pluck('posting_id')
                            ->toArray();

        $postings = Posting::whereIn('posting_id', $posting_ids)
                        ->whereNotNull('published_date')
                        ->get();

        $bar = new ProgressBar($this->output, count($carts));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($carts as $index => $cart) {

            $posting = $postings->where('posting_id', $cart->posting_id)
                            ->first();
            if(!$posting)
                continue;
            
            $cart->update([
                'details'   => $posting->toJson()
            ]);

            $bar->advance();

        }

        $bar->finish();
    }
}
