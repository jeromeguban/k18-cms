<?php

namespace App\Helpers;

use Google_Client;
use Google_Service_ShoppingContent;
use Google_Service_ShoppingContent_Price;
use Google_Service_ShoppingContent_Product;
use Google_Service_Exception;

class GoogleMerchant
{
    protected $client;
    protected $service;
    protected $product;

    public function __construct()
    {

        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('googleapi-credentials.json'));
        $this->client->setSubject(env('GMAIL_ADDRESS', 'shopnbid@hmr.ph'));
        $this->client->setApplicationName("HMR Shop N' Bid Merchant");
        $this->client->setScopes([
            "https://www.googleapis.com/auth/content",
        ]);

        $this->service = new Google_Service_ShoppingContent($this->client);
    }
 
    public function upload()
    {  
        // dd($this->service->products->insert(env('GOOGLE_MERCHANT_ID', 143038873), $this->product));
        return collect($this->service->products->insert(env('GOOGLE_MERCHANT_ID', 143038873), $this->product));
        // return collect($this->service->products->listProducts(143038873)->getResources());
    }

    public function setProduct($details)
    {
        $product = new Google_Service_ShoppingContent_Product();
        $product->setOfferId($details->sku ?? $details->id);
        $product->setTitle(ucwords(strtolower($details->name)));
        $product->setDescription(strip_tags($details->description));
        $product->setLink($details->link);
        $product->setImageLink($details->image_link);
        $product->setContentLanguage('en');
        $product->setTargetCountry('PH');
        $product->setChannel('online');
        $product->setAvailability($details->quantity ? 'in stock' : 'out of stock');
        $product->setCondition($details->condition);
        $product->setGoogleProductCategory($details->google_product_category);


        if($details->gtin)
            $product->setGtin($details->gtin);

        if($details->brand)
            $product->setBrand($details->brand);

        $price = new Google_Service_ShoppingContent_Price();
        $price->setValue($details->unit_price);
        $price->setCurrency('PHP');

        $product->setPrice($price);

        if((float)$details->sale_price > 0) {
            $sale_price = new Google_Service_ShoppingContent_Price();
            $sale_price->setValue($details->sale_price);
            $sale_price->setCurrency('PHP');
            $product->setSalePrice($sale_price);
        }

        $this->product = $product;

        return $this;
    }

    public function update()
    {
        return collect($this->service->products->insert(env('GOOGLE_MERCHANT_ID', 143038873), $this->product));
    }

    public function setNewProductDetail($details)
    {
        $product = new Google_Service_ShoppingContent_Product();
        $product->setOfferId($details->sku ?? $details->id);
        $product->setTitle(ucwords(strtolower($details->name)));
        $product->setDescription(strip_tags($details->description));
        $product->setLink($details->link);
        $product->setImageLink($details->image_link);
        $product->setContentLanguage('en');
        $product->setTargetCountry('PH');
        $product->setChannel('online');
        $product->setAvailability($details->quantity > 0 ? 'in stock' : 'out of stock');

        $price = new Google_Service_ShoppingContent_Price();
        $price->setValue($details->unit_price);
        $price->setCurrency('PHP');

        $product->setPrice($price);

        if ((float) $details->sale_price > 0) {
            $sale_price = new Google_Service_ShoppingContent_Price();
            $sale_price->setValue($details->sale_price);
            $sale_price->setCurrency('PHP');
            $product->setSalePrice($sale_price);
        }

        $this->product = $product;

        return $this;
    }

    public function delete($product)
    {   
        try {
            $this->service->products->delete(env('GOOGLE_MERCHANT_ID', 143038873), 'online:en:PH:'.$product->sku);
        } catch (Google_Service_Exception $e) {
            echo 'Error deleting product:' . $product->sku . $e->getMessage();
        }
    }

    public function getAllProducts() {

         //SHOW ALL PRODUCTS

            // $page_token = null;
            // $all_products = array();

            // do {
            //     $products = $this->service->products->listProducts(env('GOOGLE_MERCHANT_ID', 143038873), array(
            //         'pageToken' => $page_token,
            //     ));
        
            //     foreach ($products->getResources() as $product) {
            //         // Access product data
            //         $item_id = $product->getOfferId();
            //         $title = $product->getTitle();
        
            //         // Store product data
            //         $all_products[] = array(
            //             'item_id' => $item_id,
            //             'title' => $title,
            //         );
            //     }
        
            //     $page_token = $products->getNextPageToken();
            // } while ($page_token != null);
        
            // // Print or process the list of all products
            // foreach ($all_products as $product) {
            //     echo "Item ID: {$product['item_id']}, Title: {$product['title']}\n";
            // }

    }

}
