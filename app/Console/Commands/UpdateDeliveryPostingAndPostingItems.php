<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\Store;
use App\Models\StoreAddress;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use App\Models\Searchable\Posting as SearchablePosting;
use Symfony\Component\Console\Helper\ProgressBar;

class UpdateDeliveryPostingAndPostingItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:delivery-postings-postingitems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Delivery Posting and Posting Items';

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
        $stores = Store::where('store_company_code', 'HRH')
                    ->get();

        $store_id = $stores->pluck('id')
                    ->toArray();

        $store_addresses = StoreAddress::whereIn('store_id', $store_id)
                            ->get();

        $store_addresses_store_id = $store_addresses->pluck('store_id')
                            ->toArray();

        $postings = Posting::where('category', 'Retail')
                        ->whereNotNull('published_date')
                        ->where('length', '>', 0)
                        ->where('width', '>', 0)
                        ->where('height', '>', 0)
                        ->where('weight', '>', 0)
                        ->whereIn('store_id', $store_addresses_store_id)
                        ->get();

        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($postings as $posting) {

            $store_address = StoreAddress::where('store_id', $posting->store_id)
                            ->first();

            $posting->update([
                'delivery'  => 1,
                'address_id' => $store_address->address_id
            ]);

            SearchablePosting::where('posting_id', $posting->posting_id)->searchable();

            $posting_items = PostingItem::where('posting_items.posting_id', $posting->posting_id)
                                    ->whereNotNull('published_date')
                                    ->where('length', '>', 0)
                                    ->where('width', '>', 0)
                                    ->where('height', '>', 0)
                                    ->where('weight', '>', 0)
                                    ->get();

            foreach($posting_items as $posting_item) {
                $posting_item->update([
                    'delivery'  => 1,
                    'address_id'    => $store_address->address_id
                ]);

                SearchablePostingItem::where('posting_id', $posting_item->posting_id)->searchable();
            }

            $bar->advance();

        }

        $bar->finish();
            print "\n";
            $this->comment('Delivery Posting and Posting Items Successfully Updated');
    }
}
