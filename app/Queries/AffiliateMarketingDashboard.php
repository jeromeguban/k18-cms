<?php
namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderPosting;
use Illuminate\Support\Facades\DB;

class AffiliateMarketingDashboard
{
	public function query()
	{
        $year = request()->from;

        $current_year = request()->from ? Carbon::now()->format('Y') : Carbon::parse(request()->from)->format('Y');
        $current_month = request()->from ? Carbon::now()->format('m') : Carbon::parse(request()->from)->format('m');

        // remove day and month from year
        $year = substr($year, 0, 4);

		

        $current_month_total_amount = OrderPosting::select([
                        DB::raw('COALESCE(SUM(order_postings.price), 0) as current_month_total_amount'),
                    ])
                    ->join('orders','orders.id','=','order_postings.order_id')
                    ->whereNotNull('orders.payment_id')
                    ->whereNotNull('order_postings.referral_code')
                    ->whereNotNull('order_postings.affiliate_marketing_id')
                    ->whereBetween('order_postings.created_at', [Carbon::now()->startOfMonth()->toDateTimeString(), Carbon::now()->endOfMonth()->toDateTimeString()])
                    ->pluck('current_month_total_amount')->first();

        $last_month_total_amount = OrderPosting::select([
                        DB::raw('COALESCE(SUM(order_postings.price), 0) as last_month_total_amount'),
                    ])
                    ->join('orders','orders.id','=','order_postings.order_id')
                    ->whereNotNull('orders.payment_id')
                    ->whereNotNull('order_postings.referral_code')
                    ->whereNotNull('order_postings.affiliate_marketing_id')
                    ->whereBetween('order_postings.created_at', [Carbon::now()->subMonth()->startOfMonth()->toDateTimeString(), Carbon::now()->subMonth()->endOfMonth()->toDateTimeString()])
                    ->pluck('last_month_total_amount')->first();


        $yesterday_total_amount = OrderPosting::select([
                        DB::raw('COALESCE(SUM(order_postings.price), 0) as yesterday_total_amount'),
                    ])
                    ->join('orders','orders.id','=','order_postings.order_id')
                    ->whereNotNull('orders.payment_id')
                    ->whereNotNull('order_postings.referral_code')
                    ->whereNotNull('order_postings.affiliate_marketing_id')
                    ->whereBetween('order_postings.created_at', [Carbon::yesterday()->startofDay()->toDateTimeString(), Carbon::yesterday()->endOfDay()->toDateTimeString()])
                    ->pluck('yesterday_total_amount')->first();

        $amount_per_day = OrderPosting::select([
                        DB::raw('COALESCE(SUM(order_postings.price), 0) as amount_per_day'),
                    ])
                    ->join('orders','orders.id','=','order_postings.order_id')
                    ->whereNotNull('orders.payment_id')
                    ->whereNotNull('order_postings.referral_code')
                    ->whereNotNull('order_postings.affiliate_marketing_id')
                    ->whereBetween('order_postings.created_at', [Carbon::now()->startofDay()->toDateTimeString(), Carbon::now()->endOfDay()->toDateTimeString()])
                    ->pluck('amount_per_day')->first();


        for ($i = 12; $i >= 1; $i--) {
            $monthly_total_amount[$i] = OrderPosting::select([
                            DB::raw('COALESCE(SUM(order_postings.price), 0) as monthly_total_amount'),
                        ])
                        ->join('orders','orders.id','=','order_postings.order_id')
                        ->whereNotNull('orders.payment_id')
                        ->whereNotNull('order_postings.referral_code')
                        ->whereNotNull('order_postings.affiliate_marketing_id')
                        ->whereBetween('order_postings.created_at', [Carbon::create($current_year, $i)->startOfMonth()->toDateTimeString(), Carbon::create($current_year, $i)->endOfMonth()->toDateTimeString()])
                        ->pluck('monthly_total_amount')->first();

        }

        for ($i = 1; $i <= 31; $i++) {
            $total_amount_per_day[$i] = OrderPosting::select([
                            DB::raw('COALESCE(SUM(order_postings.price), 0) as total_amount_per_day'),
                        ])
                        ->join('orders','orders.id','=','order_postings.order_id')
                        ->whereNotNull('orders.payment_id')
                        ->whereNotNull('order_postings.referral_code')
                        ->whereNotNull('order_postings.affiliate_marketing_id')
                        ->whereBetween('order_postings.created_at', [Carbon::create($current_year, $current_month, $i)->startOfDay()->toDateTimeString(), Carbon::create($current_year, $current_month, $i)->endOfDay()->toDateTimeString()])
                        ->pluck('total_amount_per_day')->first();

        }

        $payment = OrderPosting::select([
            DB::raw('SUM(order_postings.price) as sub_total_amount'),
            DB::raw($current_month_total_amount.' as current_month_total_amount'),
            DB::raw($last_month_total_amount.' as last_month_total_amount'),
            DB::raw($yesterday_total_amount.' as yesterday_total_amount'),
            DB::raw($amount_per_day.' as amount_per_day'),
            DB::raw($monthly_total_amount[1].' as january_total_amount'),
            DB::raw($monthly_total_amount[2].' as february_total_amount'),
            DB::raw($monthly_total_amount[3].' as march_total_amount'),
            DB::raw($monthly_total_amount[4].' as april_total_amount'),
            DB::raw($monthly_total_amount[5].' as may_total_amount'),
            DB::raw($monthly_total_amount[6].' as june_total_amount'),
            DB::raw($monthly_total_amount[7].' as july_total_amount'),
            DB::raw($monthly_total_amount[8].' as august_total_amount'),
            DB::raw($monthly_total_amount[9].' as september_total_amount'),
            DB::raw($monthly_total_amount[10].' as october_total_amount'),
            DB::raw($monthly_total_amount[11].' as november_total_amount'),
            DB::raw($monthly_total_amount[12].' as december_total_amount'),
            DB::raw($total_amount_per_day[1].' as total_amount_as_of_day_1'),
            DB::raw($total_amount_per_day[2].' as total_amount_as_of_day_2'),
            DB::raw($total_amount_per_day[3].' as total_amount_as_of_day_3'),
            DB::raw($total_amount_per_day[4].' as total_amount_as_of_day_4'),
            DB::raw($total_amount_per_day[5].' as total_amount_as_of_day_5'),
            DB::raw($total_amount_per_day[6].' as total_amount_as_of_day_6'),
            DB::raw($total_amount_per_day[7].' as total_amount_as_of_day_7'),
            DB::raw($total_amount_per_day[8].' as total_amount_as_of_day_8'),
            DB::raw($total_amount_per_day[9].' as total_amount_as_of_day_9'),
            DB::raw($total_amount_per_day[10].' as total_amount_as_of_day_10'),
            DB::raw($total_amount_per_day[11].' as total_amount_as_of_day_11'),
            DB::raw($total_amount_per_day[12].' as total_amount_as_of_day_12'),
            DB::raw($total_amount_per_day[13].' as total_amount_as_of_day_13'),
            DB::raw($total_amount_per_day[14].' as total_amount_as_of_day_14'),
            DB::raw($total_amount_per_day[15].' as total_amount_as_of_day_15'),
            DB::raw($total_amount_per_day[16].' as total_amount_as_of_day_16'),
            DB::raw($total_amount_per_day[17].' as total_amount_as_of_day_17'),
            DB::raw($total_amount_per_day[18].' as total_amount_as_of_day_18'),
            DB::raw($total_amount_per_day[19].' as total_amount_as_of_day_19'),
            DB::raw($total_amount_per_day[20].' as total_amount_as_of_day_20'),
            DB::raw($total_amount_per_day[21].' as total_amount_as_of_day_21'),
            DB::raw($total_amount_per_day[22].' as total_amount_as_of_day_22'),
            DB::raw($total_amount_per_day[23].' as total_amount_as_of_day_23'),
            DB::raw($total_amount_per_day[24].' as total_amount_as_of_day_24'),
            DB::raw($total_amount_per_day[25].' as total_amount_as_of_day_25'),
            DB::raw($total_amount_per_day[26].' as total_amount_as_of_day_26'),
            DB::raw($total_amount_per_day[27].' as total_amount_as_of_day_27'),
            DB::raw($total_amount_per_day[28].' as total_amount_as_of_day_28'),
            DB::raw($total_amount_per_day[29].' as total_amount_as_of_day_29'),
            DB::raw($total_amount_per_day[30].' as total_amount_as_of_day_30'),
            DB::raw($total_amount_per_day[31].' as total_amount_as_of_day_31'),
        ])
        ->join('orders','orders.id','=','order_postings.order_id')
        ->whereNotNull('orders.payment_id')
        ->whereNotNull('order_postings.referral_code')
        ->whereNotNull('order_postings.affiliate_marketing_id')
        ->whereBetween('order_postings.created_at', [Carbon::parse(request()->from.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->to. ' 23:59:59')->toDateTimeString()]);

         return $payment;
	}
}
