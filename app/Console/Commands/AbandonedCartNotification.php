<?php

namespace App\Console\Commands;

use App\Helpers\Gmail;
use App\Helpers\ModelDecrypter;
use App\Models\Cart;
use Illuminate\Console\Command;

class AbandonedCartNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:abandoned-cart-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Abandoned Cart Norifications';

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
        $cart = Cart::selectedFields()
            ->joinPosting()
            ->joinPostingItem()
            ->joinCustomer()
            ->joinCustomerLoginCredential()
            ->where('carts.category', 'Retail')
            ->where('carts.expires_at', '<=', now()->toDateTimeString())
        // ->where('carts.store_id', 1)
            ->where('carts.notified', 0)
            ->get();

        $carts = (new ModelDecrypter)->decryptCollection($cart);

        if ($cart->count()) {
            foreach ($carts as $cart) {
                (new Gmail())->to(strtolower($cart->email))
                    ->view('emails.abandoned-cart')
                    ->with([
                        'subject' => 'You Have Abandoned Item on your Cart',
                        'cart' => $cart,
                    ])
                    ->send();

                Cart::where('id', $cart->id)->update(['notified' => 1]);
            }
        }

        $this->comment('Send Successfully!');
    }
}
