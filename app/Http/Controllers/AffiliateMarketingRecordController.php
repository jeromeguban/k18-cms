<?php

namespace App\Http\Controllers;

use App\Models\AffiliateMarketing;
use App\Models\Posting;

class AffiliateMarketingRecordController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', AffiliateMarketing::class);
        
        $affiliate = AffiliateMarketing::where('id',request()->id)->first();

        $query = Posting::select([
            'postings.name',
            'postings.description',
            'postings.extended_description',
            'postings.location',
            'order_postings.price',
            \DB::raw('order_postings.price * order_postings.quantity as total_price'),
            \DB::raw('IF(orders.payment_id IS NOT NULL, "PAID", "NOT PAID") as status'),
            \DB::raw('CAST((order_postings.price * order_postings.quantity) * (order_postings.commission / 100) AS DECIMAL(16,2)) as commission_price'),
            'order_postings.quantity', 
            'order_postings.referral_code', 
            'order_postings.created_at',
            'order_postings.commission'
        ])
        ->join('order_postings','order_postings.posting_id', '=', 'postings.posting_id')
        ->join('orders','orders.id', '=', 'order_postings.order_id')
        ->where('order_postings.referral_code',$affiliate->code)
        ->whereNull('order_postings.cancelled_date')
        ->OrderBy('order_postings.created_at');

        if(request()->from && request()->to){
              $query->whereBetween('order_postings.created_at',[request()->from, request()->to]);
        }
                
        return $this->response($query);
    }

}
