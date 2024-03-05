<?php

namespace App\Http\Controllers;

use App\Helpers\GoogleMerchant;
use App\Models\GoogleMerchantProduct;
use App\Models\Posting;
use App\Models\PostingItem;
use Illuminate\Http\Request;

class ProductGoogleMerchantPublishController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PostingItem $posting_item)
    {
        $request->validate([
            'condition' => 'required',
            'gtin'      => [
                'required',
                'min:8',
                'max:14',
                'regex:/^\\d+$/',
            ],
            'category_id'   => [
                'required',
                'exists:google_product_categories,id'
            ],
            'brand_id'   => [
                'nullable',
                'exists:brands,brand_id'
            ],
        ],[
            'gtin.regex'    => 'The :attribute only accepts numerical value.'
        ],[
            'condition'     => 'Condition',
            'category_id'   => 'Category',
            'brand_id'      => 'Brand',
            'gtin'          => 'GTIN (UPC)'
        ]);

        // abort(403, "Something went wrong");



        \DB::transaction(function() use ($posting_item, $request) {


            if(!$posting_item->google_merchant_product_id) {

                $google_merchant_product = GoogleMerchantProduct::create([
                    'posting_item_id'               => $posting_item->id,
                    'condition'                     => $request->condition,
                    'brand_id'                      => $request->brand_id,
                    'brand_name'                    => $request->brand_name,
                    'gtin'                          => $request->gtin,
                    'google_product_category_id'    => $request->category_id,
                    'created_by'                    => auth()->id(),
                    'modified_by'                   => auth()->id()
                ]);

                $posting_item->update([
                    'google_merchant_product_id'    => $google_merchant_product->id
                ]);

            } else {

                GoogleMerchantProduct::where('id', $posting_item->google_merchant_product_id)->update([
                    'condition'                     => $request->condition,
                    'google_product_category_id'    => $request->category_id,
                    'brand_id'                      => $request->brand_id,
                    'brand_name'                    => $request->brand_name,
                    'gtin'                          => $request->gtin,
                    'modified_by'                   => auth()->id()
                ]);

            }

            $product = Posting::select([
                            \DB::raw('CONCAT("'.env('HMR_PH_URL', 'https://hmr.ph').'/product/", postings.slug, "?from_gmc=1") AS link'),
                            \DB::raw('CONCAT("'.env('HMR_PH_URL', 'https://hmr.ph').'", postings.banner) AS image_link'),
                            'posting_items.*'
                        ])
                        ->join('posting_items', 'posting_items.posting_id', '=', 'postings.posting_id')
                        ->where('postings.posting_id', $posting_item->posting_id)
                        ->first();

            abort_if(!$product, 500, 'Something went wrong. Please try again.');

            $product->sale_price = (float) $product->unit_price > (float) $product->suggested_retail_price ? $product->suggested_retail_price : 0.00;
            $product->condition = $request->condition;
            $product->google_product_category = $request->category_id;
            $product->gtin = $request->gtin;
            $product->brand = $request->brand_name;

            (new GoogleMerchant)->setProduct($product)
                ->upload();

        });


        return [
            'success'   => 1
        ];
    }

}
