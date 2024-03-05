<?php

namespace App\Console\Commands;

use App\Helpers\GoogleMerchant;
use App\Models\GoogleMerchantProduct;
use App\Models\Posting;
use App\Models\PostingItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class DeleteGoogleMerchantProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:google-merchant-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Google Merchant Products when zero quantity ';

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

        $posting_items = PostingItem::whereNotNull('google_merchant_product_id')
            ->where('quantity', 0)
            ->get();

        $bar = new ProgressBar($this->output, count($posting_items));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach ($posting_items as $posting_item) {
            try {

                abort_if(!$posting_item, 500, 'Something went wrong. Please try again.');

                $google_merchant_product = GoogleMerchantProduct::where('posting_item_id', $posting_item->id);
                
                $google_merchant_product->delete();

                $posting_item->update([
                    'google_merchant_product_id' => null
                ]);

                (new GoogleMerchant)->delete($posting_item);

            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            $bar->advance();

        }

        $bar->finish();
        print "\n";
        $this->comment('Google Merchant Successfully Deleted!');

    }
}
