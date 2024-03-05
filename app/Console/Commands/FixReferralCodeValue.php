<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderPosting;
use App\Models\Cart;
use Symfony\Component\Console\Helper\ProgressBar;

class FixReferralCodeValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:referral-code-value';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Referral code value to null';

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
        $order_postings = OrderPosting::where('referral_code', "")->get();

        $carts = Cart::where('referral_code', "")->get();

        foreach($order_postings as $index => $order_posting) {
          
            $order_posting->update([
                'referral_code'   => null
            ]);

        }

        foreach($carts as $index => $cart) {
            
            $cart->update([
                'referral_code'   => null
            ]);

        }

    }
}
