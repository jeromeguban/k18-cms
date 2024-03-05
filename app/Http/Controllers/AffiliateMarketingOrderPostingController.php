<?php

namespace App\Http\Controllers;

use App\Models\Posting;
use App\Models\AffiliateMarketing;
use Illuminate\Support\Facades\DB;

class AffiliateMarketingOrderPostingController extends Controller
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

        $query = Posting::select([
            'postings.name',
            'postings.description',
            'postings.extended_description',
            'postings.location',
            'order_postings.price',
            DB::raw('order_postings.price * order_postings.quantity as total_price'),
            DB::raw('IF(orders.payment_id IS NOT NULL, "PAID", "NOT PAID") as status'),
            DB::raw('CAST((order_postings.price * order_postings.quantity) * (order_postings.commission / 100) AS DECIMAL(16,2)) as commission_price'),
            'order_postings.quantity',
            'order_postings.referral_code',
            'order_postings.created_at',
            'order_postings.commission',
            DB::raw('CONCAT(affiliate_marketings.first_name,"",affiliate_marketings.last_name) as marketer'),
        ])
        ->join('order_postings', 'order_postings.posting_id', '=', 'postings.posting_id')
        ->join('orders', 'orders.id', '=', 'order_postings.order_id')
        ->join('affiliate_marketings', 'affiliate_marketings.id', '=', 'order_postings.affiliate_marketing_id')
        ->whereNull('order_postings.cancelled_date')
        ->OrderBy('order_postings.created_at', 'DESC')
        ->searchable();
        
        
        
        if (request()->from && request()->to) {
            $query->whereBetween('order_postings.created_at', [request()->from, request()->to]);
        }

        if (request()->from && request()->to && request()->marketer_id) {
            $query->whereBetween('order_postings.created_at', [request()->from, request()->to]);
            $query->where('order_postings.affiliate_marketing_id', request()->marketer_id);
        }

        if (request()->marketer_id) {
            $query->where('order_postings.affiliate_marketing_id', request()->marketer_id);
        }

        return $this->response($query);
    }
}
