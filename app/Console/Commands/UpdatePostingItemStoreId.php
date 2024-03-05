<?php

namespace App\Console\Commands;

ini_set('memory_limit','-1');
ini_set('max_execution_time', 14400);
use App\Models\Store;
use App\Models\Posting;
use App\Models\Product;
use App\Models\PostingTag;
use App\Models\PostingItem;
use Illuminate\Console\Command;
use App\Helpers\ExtrimApiHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Helper\ProgressBar;

class UpdatePostingItemStoreId extends Command
{
    protected $posting_items, $posting, $store, $item, $access_token;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:posting-item-store-id {store_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Item Store ID';

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
    public function handle(){

       $this->access_token = (new ExtrimApiHelper)->RetrieveToken();

       $this->posting_items = PostingItem::where('store_id',$this->argument('store_id'))->get();
    // $this->posting_items = PostingItem::where('sku',"LAS138682")->get();

       $bar = new ProgressBar($this->output, count($this->posting_items));

       $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

       $bar->start();      
      
       foreach( $this->posting_items as $posting_item) {

         try {

                \DB::transaction(function()use($posting_item){

                    $this->getStore();
                    $this->getItemDetails($posting_item)
                         ->updatePostingItem($posting_item);

                });
                 
            }
            catch (\Exception $e) {
                    
         }

            $bar->advance();
        }

        $bar->finish();
        print "\n";
        $this->comment('Posting Successfully Updated!');
           
    }  


    private function getStore(){

        $this->store = Store::select(DB::raw('CONCAT(address_line, extended_address) AS location'),'stores.reference_id','stores.id')
                        ->where('id', $this->argument('store_id'))
                        ->first();

        return $this;
    }
   

    private function getItemDetails($posting_item){   

        $barcode = $posting_item->sku;

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer '.$this->access_token,
        ])
        ->accept('application/json')
        ->get('https://xv2.hmrphils.com/api/item/store-item-details', [
            'item_barcode' => $barcode,
            'store_id' =>  $this->store->reference_id
        ]);
       
        $this->item = json_decode($response->body());

        return $this;

    }


    private function updatePostingItem($posting_item)
    {
        
        $item = PostingItem::where('id', $posting_item->id)->first();

        $item->update([

            'item_store_id'  => $this->item ? $this->item->item_qty_id : null,

        ]);
    
        return $this;
    }

}
