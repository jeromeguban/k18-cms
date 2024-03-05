<?php

namespace App\Jobs;

use App\Models\Tag;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $row;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($row)
    {
        
        $this->row = $row;
    }

    public function multipleexplode ($delimiters,$string) {

        $phase = str_replace($delimiters, $delimiters[0], $string);
        $processed = explode($delimiters[0], $phase);
        return  $processed;
        
   }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        

        $hmr_categories = str_replace(' ', ' ',$this->multipleexplode(array("&amp;",">",",",".","|",":"),$this->row['product_cat']));

         
        $category = Category::selectedFields()
                     ->whereNull('deleted_at')
                     ->where(function($query) use($hmr_categories){ 

                     foreach($hmr_categories as $hmr_category){
                         $query->orWhere('category_name', 'LIKE','%'.$hmr_category.'%');
                     }
            
        })->get();

        $sub_category = SubCategory::selectedFields()
                         ->whereNull('deleted_at')
                         ->where(function($query) use($hmr_categories){ 

                         foreach($hmr_categories as $hmr_category){
                             $query->orWhere('sub_category_name', 'LIKE','%'.$hmr_category.'%');
                         }
            
        })->get();

        $tag = Tag::selectedFields()
                 ->whereNull('deleted_at')
                 ->where(function($query) use($hmr_categories){ 

                 foreach($hmr_categories as $hmr_category){
                     $query->orWhere('name', 'LIKE','%'.$hmr_category.'%');
                 }

        })->get();


        $barcode = str_replace(' ', '', $this->multipleexplode(array(" ","-ra","tr-","Zhen-","JLRPra","-JLRP","RSJLRPra","-JL","TR-","SA-","JLrpra","(",")","=","/","-1","_","-","&amp;",">",",",".","|",":"),$this->row['sku']));

         Product::updateOrCreate([
             'post_title'        =>  $this->row['post_title'],
             'post_name'         =>  $this->row['post_name'],
             ],[
             'store'             => $this->row['store'],  //set store name
             'store_id'          => $this->row['store_id'],        //set store id
             'store_reference_id'=> $this->row['store_reference_id'],            //set store ref id
             'post_title'        => $this->row['post_title'],
             'post_name'         => $this->row['post_name'],
             'hmr_id'            => $this->row['id'],
             'post_excerpt'      => $this->row['post_excerpt'],
             'post_content'      => $this->row['post_content'],
             'post_status'       => $this->row['post_status'],
             'post_date'         => gmdate("Y-m-d H:i:s",($this->row['post_date'] - 25569) * 86400),
             'sku'               => $barcode[0],
             'stock'             => $this->row['stock'],
             'regular_price'     => $this->row['regular_price'] ?? null,
             'sale_price'        => $this->row['sale_price'] ?? null,
             'weight'            => $this->row['weight'],
             'length'            => $this->row['length'],
             'width'             => $this->row['width'],
             'height'            => $this->row['height'],
             'stock_status'      => $this->row['stock_status'],
             'backorders'        => $this->row['backorders'],
             'manage_stock'      => $this->row['manage_stock'],
             'tax_status'        => $this->row['tax_status'],
             'tax_product_cat'   => json_encode($hmr_categories),
             'tax_product_tag'   => $this->row['product_tag'],
             'tax_product_shipping_class'=> $this->row['product_shipping_class'],
             'categories'        => json_encode($category->pluck('category_id')->toArray()),
             'sub_categories'    => json_encode($sub_category->pluck('sub_category_id')->toArray()),
             'tags'              => json_encode($tag->pluck('id')->toArray()),
             'images'            => json_encode(array_map('trim',str_replace("https://hmr.ph/wp-content/uploads","/images/postings",explode("|",$this->row['images'])))),
         
         ]);
    }
}
