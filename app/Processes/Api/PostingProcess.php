<?php

namespace App\Processes\Api;

use App\Models\Brand;
use App\Models\Store;
use App\Models\Posting;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PostingProcess
{

	protected $request, $posting, $store;


	 /**
     * Create a new process instance.
     *
     * @return void
     */
	public function __construct($request = null)
	{
		$this->request = $request ? (object) $request : request();
        $this->store = Store::where('reference_id', $this->request->store_id)->firstOrFail();
	}


	/**
     * Execute find process.
     *
     * @return void
    */

    public function find($id)
    {

    	$this->posting = Posting::findOrFail($id);

    }


    /**
     * Retrive posting.
     *
     * @return
    */

    public function posting()
    {

    	return $this->posting;

    }


	/**
     * Execute create process.
     *
     * @return void
    */

    public function create()
    {

    	DB::transaction(function(){

            $this->createPosting();

    	});

    }

    /**
     * Create posting.
     *
     * @return void
    */

    private function createPosting()
    {

    	$this->posting = Posting::updateOrCreate([
            'auction_id'                =>  $this->request->auction_id,
            'lot_id'                    =>  $this->request->lot_id,
        ],
        [   'name'                      =>  $this->request->name,
            'description'               =>  $this->request->description,
            'extended_description'      =>  $this->request->extended_description,
            'store_id'                  =>  $this->store->id,
            'term_id'                   =>  1,
            'auction_number'            =>  $this->request->auction_number ?? null,
            'auction_name'              =>  $this->request->auction_name ?? null,
            // 'bid_amount'                =>  $this->request->request->posting->bid_amount ?? null,
            'starting_time'             =>  $this->request->starting_time ?? null,
            'ending_time'               =>  $this->request->ending_time ?? null,
            'payment_period'            =>  $this->request->payment_period ?? null,
            'buyers_premium'            =>  $this->request->buyers_premium ?? null,
            'vat'                       =>  $this->request->vat ?? 0,
            'duties'                    =>  $this->request->duties ?? 0,
            'other_fees'                =>  $this->request->other_fees ?? 0,
            'increment_percentage'      =>  $this->request->increment_percentage ?? 0,
            'starting_amount'           =>  $this->request->starting_amount ?? null,
            'notarial_fee'              =>  $this->request->notarial_fee ?? null,
            'processing_fee'            =>  $this->request->processing_fee ?? null,
            'reserved_price'            =>  $this->request->reserved_price ?? null,
            'bidding'                   =>  $this->request->bidding ?? 0,
            'buy_now'                   =>  $this->request->buy_now ?? 0,
            'viewing'                   =>  $this->request->viewing ?? 0,
            'pickup'                    =>  $this->request->pickup ?? 0,
            'delivery'                  =>  $this->request->delivery ?? 0,
            'buy_now_price'             =>  $this->request->buy_now_price ?? 0,
            'buy_back_percentage'       =>  $this->request->buy_back_percentage ?? 0,
            'buy_now_price'             =>  $this->request->buy_now_price ?? 0,
            'viewing_price'             =>  $this->request->viewing_price ?? 0,
            'length'                    =>  $this->request->length ?? 0,
            'width'                     =>  $this->request->width ?? 0,
            'height'                    =>  $this->request->height ?? 0,
            'weight'                    =>  $this->request->weight ?? 0,
            'bidding_type'              =>  $this->request->bidding_type,
            'increment_type'            =>  $this->request->increment_type,
            'lot_number'                =>  $this->request->lot_number ?? null,
            'published_date'            =>  $this->request->published_date ?? null,
            'ordered_date'              =>  $this->request->ordered_date ?? null,
            'for_approval_status'       =>  $this->request->for_approval_status ?? null,
            'approved_by'               =>  $this->request->approved_by ?? null,
            'approved_date'             =>  $this->request->approved_date ?? null,
            'item_category_type'        =>  $this->request->item_category_type ?? null,
            'attribute_data'            =>  $this->request->attribute_data ? str_replace("\\","", $this->request->attribute_data) : null,
            'slug'                      =>  $this->generateSlug(),
            'category'                  =>  'Auction',
    		'categories'                =>  count($this->category()) ? json_encode( $this->category() ) : null,
            'sub_categories'            =>  count($this->subCategory()) ? json_encode( $this->subCategory() ) : null,
            'brands'                    =>  count($this->brand()) ? json_encode( $this->brand() ) : null,
            'images'                    =>  json_encode($this->request->images),
            'banner'                    =>  $this->request->banner ?? null,
            'location'                  =>  $this->request->location ?? null,
            'viewing_details'           =>  $this->request->viewing_details ? str_replace( [ "\"[", "]\"" ], [ "[", "]" ],  str_replace( [ "\"{", "}\"" ],[ "{", "}" ],str_replace("\\", "",json_encode( $this->request->viewing_details)))) : null,
            'category_sequence'         =>  2,
            'created_by'                =>  1,
            'modified_by'               =>  1,
    	]);

    	return $this;

    }

    public function generateSlug()
    {
        $slug = str_replace("\\", "", str_replace("/", "", substr(Str::kebab(strtolower($this->request->name)), 0, 35)));

        return $slug.'-'.uniqid(5).substr(0, 4);
    }

    public function category()
    {

        $category =  Category::selectedFields()
                        ->where(function($query){
                            foreach($this->request->categories as $category){
                                $query->orWhere('category_name', 'LIKE','%'.$category.'%');
                            }
                        })->get();

       return $category->pluck('category_id')->toArray();


    }


    public function subCategory()
    {

        $sub_category = SubCategory::selectedFields()
                                ->where(function($query){
                                    foreach($this->request->sub_categories as $sub_category){
                                        $query->orWhere('sub_category_name', 'LIKE','%'.$sub_category.'%');
                                    }
                               })->get();

         return $sub_category->pluck('sub_category_id')->toArray();

    }

    public function brand()
    {

        $brand =  Brand::selectedFields()
                        ->where(function($query){
                            foreach($this->request->brands as $brand){
                                $query->orWhere('brand_name', 'LIKE','%'.$brand.'%');
                            }
                        })->get();

       return $brand->pluck('brand_id')->toArray();


    }


}
