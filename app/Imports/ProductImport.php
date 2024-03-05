<?php

namespace App\Imports;
ini_set('memory_limit',1);
ini_set('max_execution_time', 14400);
use Storage;
use App\Models\Tag;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Jobs\ProductImportJob;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{

//     public function multipleexplode ($delimiters,$string) {

//         $phase = str_replace($delimiters, $delimiters[0], $string);
//         $processed = explode($delimiters[0], $phase);
//         return  $processed;
        
//    }


    public function collection(Collection $rows)
    {

       
        foreach ($rows as $row) 
        {
          
            dispatch(new ProductImportJob($row));

          
        //    $hmr_categories = str_replace(' ', ' ',$this->multipleexplode(array("&amp;",">",",",".","|",":"),$row['product_cat']));

         
        //    $category = Category::selectedFields()
        //                 ->whereNull('deleted_at')
        //                 ->where(function($query) use($hmr_categories){ 

        //                 foreach($hmr_categories as $hmr_category){
        //                     $query->orWhere('category_name', 'LIKE','%'.$hmr_category.'%');
        //                 }
               
        //    })->get();

        //    $sub_category = SubCategory::selectedFields()
        //                     ->whereNull('deleted_at')
        //                     ->where(function($query) use($hmr_categories){ 

        //                     foreach($hmr_categories as $hmr_category){
        //                         $query->orWhere('sub_category_name', 'LIKE','%'.$hmr_category.'%');
        //                     }
               
        //    })->get();

        //    $tag = Tag::selectedFields()
        //             ->whereNull('deleted_at')
        //             ->where(function($query) use($hmr_categories){ 

        //             foreach($hmr_categories as $hmr_category){
        //                 $query->orWhere('name', 'LIKE','%'.$hmr_category.'%');
        //             }

        //    })->get();


        //    $barcode = str_replace(' ', '', $this->multipleexplode(array(" ","-ra","tr-","Zhen-","JLRPra","-JLRP","RSJLRPra","-JL","TR-","SA-","JLrpra","(",")","=","/","-1","_","-","&amp;",">",",",".","|",":"),$row['sku']));

        //     Product::updateOrCreate([
        //         'post_title'        =>  $row['post_title'],
        //         'post_name'         =>  $row['post_name'],
        //         ],[
        //         'store'             => $row['store'],  //set store name
        //         'store_id'          => $row['store_id'],        //set store id
        //         'store_reference_id'=> $row['store_reference_id'],            //set store ref id
        //         'post_title'        => $row['post_title'],
        //         'post_name'         => $row['post_name'],
        //         'hmr_id'            => $row['id'],
        //         'post_excerpt'      => $row['post_excerpt'],
        //         'post_content'      => $row['post_content'],
        //         'post_status'       => $row['post_status'],
        //         'post_date'         => gmdate("Y-m-d H:i:s",($row['post_date'] - 25569) * 86400),
        //         'sku'               => $barcode[0],
        //         'stock'             => $row['stock'],
        //         'regular_price'     => $row['regular_price'] ?? null,
        //         'sale_price'        => $row['sale_price'] ?? null,
        //         'weight'            => $row['weight'],
        //         'length'            => $row['length'],
        //         'width'             => $row['width'],
        //         'height'            => $row['height'],
        //         'stock_status'      => $row['stock_status'],
        //         'backorders'        => $row['backorders'],
        //         'manage_stock'      => $row['manage_stock'],
        //         'tax_status'        => $row['tax_status'],
        //         'tax_product_cat'   => json_encode($hmr_categories),
        //         'tax_product_tag'   => $row['product_tag'],
        //         'tax_product_shipping_class'=> $row['product_shipping_class'],
        //         'categories'        => json_encode($category->pluck('category_id')->toArray()),
        //         'sub_categories'    => json_encode($sub_category->pluck('sub_category_id')->toArray()),
        //         'tags'              => json_encode($tag->pluck('id')->toArray()),
        //         'images'            => json_encode(array_map('trim',str_replace("https://hmr.ph/wp-content/uploads","/images/postings",explode("|",$row['images'])))),
            
        //     ]);
            
            // convert excel date to actual date
            // cause excel gives number not a exact date
            // dd(gmdate("Y/m",($row['post_date'] - 25569) * 86400)); 
            
            // $images = explode("|",$row['images']);

            // foreach ($images as $image) 
            // {
            //     try {

            //         $url= trim($image);

            //         $contents = file_get_contents($url);
    
            //         $name = substr( $url, strrpos($url, '/') + 1);
            
            //         $location = public_path('images/postings/'.gmdate("Y/m/",($row['post_date'] - 25569) * 86400).$name);
            //         $location_path = public_path('images/postings/'.gmdate("Y/m/",($row['post_date'] - 25569) * 86400));
            
            //         if (!file_exists($location_path)) {
            //             mkdir($location_path, 0755 , true);
            //         }
                
            //         $image = Image::make($contents);

            //         $image->save($location);

            //     }
            //     catch (\Exception $e) {
            //         continue;
            //     }
            // }
        }
    }

    public function batchSize(): int
    {
        return 250;
    }

    public function chunkSize(): int
    {
        return 250;
    }

}

