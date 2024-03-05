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

class GeneratePosting extends Command
{
    protected $products, $posting, $store, $item, $access_token;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:retail-posting {store_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Retail Posting for Hmr.com';

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

      $this->access_token = (new ExtrimApiHelper)->RetrieveToken();

       $this->products = Product::where('store','LIKE','%'.$this->argument('store_name').'%')->get();

       $bar = new ProgressBar($this->output, count($this->products));

       $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

       $bar->start();      
      
       foreach( $this->products as $product) {

         try {

                \DB::transaction(function()use($product){

                    $this->getItemDetails($product);

                    if($this->item)
                        $validate_posting_item = PostingItem::where('posting_items.item_store_id', $this->item->item_qty_id)                      
                                                    ->first();
                    
                    if($this->item && $validate_posting_item && $product->tax_product_cat ==  $validate_posting_item->condition )
                        $this->updatePostingItemQty($validate_posting_item, $product);


                    if($this->item && !$validate_posting_item)
                        $this->getStore($product)
                            ->createPosting($product)
                            ->createItem($product);
                    
                });
                 
            }
            catch (\Exception $e) {
                    
         }

            $bar->advance();
        }

        $bar->finish();
        print "\n";
        $this->comment($this->argument('store_name').' '.'Retail Posting Successfully Generated!');
           
    }  

    private function multipleexplode ($delimiters,$string) {

        $phase = str_replace($delimiters, $delimiters[0], $string);
        $processed = explode($delimiters[0], $phase);
        return  $processed;
        
    }
 
   

    private function getItemDetails($product) 
    {   

        $barcode = str_replace(' ', '', $this->multipleexplode(array(" ","-ra","tr-","Zhen-","JLRPra","-JLRP","RSJLRPra","-JL","TR-","SA-","JLrpra","(",")","=","/","-1","_","-","&amp;",">",",",".","|",":"),$product->sku));

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer '.$this->access_token,
        ])
        ->accept('application/json')
        ->get('https://xv2.hmrphils.com/api/item/store-item-details', [
            'item_barcode' => $barcode[0],
            'store_id' => $product->store_reference_id,
        ]);
       
        $this->item = json_decode($response->body());

        return $this;

    }


    private function getStore($product) {

        $this->store = Store::select(DB::raw('CONCAT(address_line, extended_address) AS location'))
                        ->where('id', $product->store_id)
                        ->first();

        return $this;
    }
    

    private function createPosting($product) 
    {

       $image  = json_decode($product->images);
       $banner = $image[0];

       $this->posting = Posting::updateOrCreate([
       
        'slug'                 => $product->post_name,
        'name'                 => $product->post_title,
        'item_id'              => $this->item ? $this->item->item_id : null,

        ],[

        'description'          => $product->post_excerpt,
        'extended_description' => null,
        'location'             => $this->store ? $this->store->location : null,
        'banner'               => $banner,
        'images'               => $product->images,
        'categories'           => $product->categories,
        'sub_categories'       => $product->sub_categories,
        'brands'               => null,
        'seo_keywords'         => null,
        'category'             => 'Retail',
        'tags'                 => $product->tags ?? null,
        'bidding'              => 0,
        'buy_now'              => 0,
        'pickup'               => 1,
        'delivery'             => 0,
        'unit_price'           => $product->regular_price ?? 0 ,
        'length'               => $product->length ?? 0,
        'width'                => $product->width ?? 0,
        'height'               => $product->height ?? 0,
        'weight'               => $product->weight ?? 0,
        'suggested_retail_price' => $product->sale_price ? $product->sale_price : $product->regular_price,
        'store_id'                => $product->store_id,
        'created_by'              => 1,
        'modified_by'             => 1,
        'published_by'            => 1,
        'published_date'          => $product->post_date,
        'category_sequence'       => 1

       ]);

       $this->createTag();
    
       return $this;

    }

    private function createItem($product) {
        

        PostingItem::updateOrCreate([

            'item_store_id'         => $this->item ? $this->item->item_qty_id : null,

            ],[

            'upc'                    => $this->item ? $this->item->upc : null,
            'sku'                    => $this->item ? $this->item->item_barcode : null,
            'images'                 => $product->images,
            'name'                   => $product->post_title,
            'description'            => $product->post_title,
            'extended_description'   => $product->post_excerpt,
            'condition'              => $product->tax_product_tag ?? 'AS IS',
            'quantity'               => $product->stock,
            'store_id'               => $product->store_id,
            'store_location'         => $this->store ? $this->store->location : null,
            'item_id'                => $this->item ? $this->item->item_id : null,
            'unit_price'             => $product->regular_price ?? 0,
            'suggested_retail_price' => $product->sale_price ? $product->sale_price : $product->regular_price,
            // 'discounted_price'       => $product->sale_price ?? 0,
            'length'                 => $product->length ?? 0,
            'width'                  => $product->width ?? 0,
            'height'                 => $product->height ?? 0,
            'weight'                 => $product->weight ?? 0,
            'posting_id'             => $this->item ? $this->posting->posting_id : null,
            'created_by'             => 1,
            'modified_by'            => 1,
            'published_by'           => 1,
            'published_date'         => $product->post_date
    
           ]);

           $total_quantity = PostingItem::where('posting_items.posting_id', $this->posting->posting_id)->sum('quantity');

           $this->posting->update([

            'quantity' => $total_quantity,

           ]);


        return $this;
    }


    private function createTag() {

        $tags  = json_decode($this->posting->tags);

        $count = count(json_decode($this->posting->tags));
 
        if( $count >= 1){
 
             foreach($tags as $tag){
 
                 PostingTag::updateOrCreate([

                        'posting_id' => $this->posting->posting_id,
                        'tag_id'     => $tag,
        
                    ],[
                        'posting_id' => $this->posting->posting_id,
                        'tag_id'     => $tag,
                        'created_by' => 1,
                        'published_date' => $this->posting->published_date,
                 ]);
             }
         }
    }
    

    private function updatePostingItemQty($validate_posting_item, $product) {

        $posting_item = PostingItem::where('posting_items.item_store_id',$validate_posting_item->id)->first();

        $posting_item->update([

            'quantity'  => (int)$posting_item->quantity + (int)$product->stock,

        ]);
        
    }
    

}
