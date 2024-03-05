<?php

namespace App\Console\Commands;

use App\Helpers\GoogleMerchant;
use App\Models\Posting;
use App\Models\PostingItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class UpdateGoogleMerchantProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:google-merchant-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Google Merchant Products when zero quantity ';

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
        // ->where('quantity', 0)
            ->get();

        $bar = new ProgressBar($this->output, count($posting_items));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach ($posting_items as $posting_item) {
            try {

                $product = Posting::select([
                    DB::raw('CONCAT("' . env('HMR_PH_URL', 'https://hmr.ph') . '/product/", postings.slug, "?from_gmc=1") AS link'),
                    DB::raw('CONCAT("' . env('HMR_PH_URL', 'https://hmr.ph') . '", postings.banner) AS image_link'),
                    'posting_items.*',
                ])
                    ->join('posting_items', 'posting_items.posting_id', '=', 'postings.posting_id')
                    ->where('postings.posting_id', $posting_item->posting_id)
                    ->first();

                abort_if(!$product, 500, 'Something went wrong. Please try again.');

                $product->sale_price = (float) $product->unit_price > (float) $product->suggested_retail_price ? $product->suggested_retail_price : 0.00;

                (new GoogleMerchant)->setNewProductDetail($product)->update();

            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            $bar->advance();

        }

        $bar->finish();
        print "\n";
        $this->comment('Products Successfully Updated!');

    }
}
