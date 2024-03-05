<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3000);
use App\Models\AffiliateMarketing;
use App\Models\Posting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AffiliateMarketingResult
{
	public static function query()
	{   
        if(request()->id)
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
            'order_postings.commission',
            \DB::raw('CONCAT(affiliate_marketings.first_name,"",affiliate_marketings.last_name) as marketer'),
        ])
        ->join('order_postings','order_postings.posting_id', '=', 'postings.posting_id')
        ->join('orders','orders.id', '=', 'order_postings.order_id')
        ->join('affiliate_marketings','affiliate_marketings.code', '=', 'order_postings.referral_code')
       
       
        ->whereNull('order_postings.cancelled_date')
        ->OrderBy('order_postings.created_at');
        
        if(request()->id && request()->from && request()->to){
               $query->where('order_postings.referral_code',$affiliate->code)
                     ->where('affiliate_marketings.id',$affiliate->id)
                     ->whereBetween('order_postings.created_at',[Carbon::parse(request()->from.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->to.' 23:59:59')->toDateTimeString()]);
        }

         if(!request()->id && request()->from && request()->to){
               $query->whereBetween('order_postings.created_at',[Carbon::parse(request()->from.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->to.' 23:59:59')->toDateTimeString()]);
        }

        if (!request()->marketer_id && request()->from && request()->to) {
            $query->whereBetween('order_postings.created_at', [Carbon::parse(request()->from . ' 00:00:00')->toDateTimeString(), Carbon::parse(request()->to . ' 23:59:59')->toDateTimeString()]);
        }

        if (request()->id && request()->from && request()->to) {
            $query->where('affiliate_marketings.id', request()->marketer_id)
                  ->whereBetween('order_postings.created_at', [Carbon::parse(request()->from . ' 00:00:00')->toDateTimeString(), Carbon::parse(request()->to . ' 23:59:59')->toDateTimeString()]);
        }

        if (request()->marketer_id) {
            $query->where('order_postings.affiliate_marketing_id', request()->marketer_id);
        }


		return $query; 
	}
}
