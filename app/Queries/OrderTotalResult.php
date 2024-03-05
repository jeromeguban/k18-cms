<?php

namespace App\Queries;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);

use App\Models\Auction;
use App\Models\OrderPosting;
use App\Models\Order;
use App\Models\Posting;
use App\Models\Payment;
use App\Models\PostingItem;
use App\Models\Store;
use Carbon\Carbon;
use App\Models\UserStore;

class OrderTotalResult
{
	public static function query()
	{
        $year = request()->start_date;

        $year_now = request()->start_date ? Carbon::now()->format('Y') : Carbon::parse(request()->start_date)->format('Y');
        $month_now = request()->start_date ? Carbon::now()->format('m') : Carbon::parse(request()->start_date)->format('m');

        $year = substr($year, 0, 4);

        $date_now = Carbon::now()->toDateString();

        $posting_items = PostingItem::selectedFields();

        $postings = Posting::selectedFields();

        $auctions = Auction::selectedFields();

        $order = Order::selectedFields()
                    ->whereNotNull('orders.payment_id');

        $order_pending = Order::selectedFields()
            ->where('orders.order_status', 'Pending');

        $total_amount_this_monthly = Payment::select([
                \DB::raw('COALESCE(SUM(payments.total_amount), 0) as this_month_total_amount'),
            ])
            ->whereBetween('payments.created_at', [Carbon::now()->startOfMonth()->toDateTimeString(), Carbon::now()->endOfMonth()->toDateTimeString()])
            ->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'))
            ->pluck('this_month_total_amount');


        $total_amount_last_monthly = Payment::select([
                \DB::raw('COALESCE(SUM(payments.total_amount), 0) as last_month_total_amount'),
            ])
            ->whereBetween('payments.created_at', [Carbon::now()->subMonth()->startOfMonth()->toDateTimeString(), Carbon::now()->subMonth()->endOfMonth()->toDateTimeString()])
            ->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'))
            ->pluck('last_month_total_amount');


        $total_amount_yesterday = Payment::select([
                \DB::raw('COALESCE(SUM(payments.total_amount), 0) as yesterday_total_amount'),
            ])
            ->whereBetween('payments.created_at', [Carbon::yesterday()->startofDay()->toDateTimeString(), Carbon::yesterday()->endOfDay()->toDateTimeString()])
            ->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'))
            ->pluck('yesterday_total_amount');

        $total_amount_today = Payment::select([
                \DB::raw('COALESCE(SUM(payments.total_amount), 0) as today_total_amount'),
            ])
            ->whereBetween('payments.created_at', [Carbon::now()->startofDay()->toDateTimeString(), Carbon::now()->endOfDay()->toDateTimeString()])
            ->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'))
            ->pluck('today_total_amount');

        for ($i = 12; $i >= 1; $i--) {
            $total_amount_monthly[$i] = Payment::select([
                            \DB::raw('COALESCE(SUM(payments.total_amount), 0) as month_total_amount'),
                        ])
                        ->whereBetween('payments.created_at', [Carbon::create($year_now, $i)->startOfMonth()->toDateTimeString(), Carbon::create($year_now, $i)->endOfMonth()->toDateTimeString()])
                        ->pluck('month_total_amount');

        }

        for ($i = 1; $i <= 31; $i++) {

            $total_amount_per_day[$i] = Payment::select([
                            \DB::raw('COALESCE(SUM(payments.total_amount), 0) as day_total_amount'),
                        ])
                        ->whereBetween('payments.created_at', [Carbon::create($year_now, $month_now, $i)->startOfDay()->toDateTimeString(), Carbon::create($year_now, $month_now, $i)->endOfDay()->toDateTimeString()])
                        ->pluck('day_total_amount');

        }


        if(!request()->start_date) {

            $postings->where('postings.published_date', 'LIKE', "%".$date_now."%");

            $auctions->where('auctions.published_date', 'LIKE', "%".$date_now."%");

            $posting_items->where('posting_items.published_date', 'LIKE', "%".$date_now."%");

            $order->where('orders.created_at', 'LIKE', "%".$date_now."%");

            $order_pending->where('orders.created_at', 'LIKE', "%".$date_now."%");

        }

        if(request()->start_date) {

            $postings->whereBetween('postings.published_date', [Carbon::parse(request()->start_date.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->end_date. ' 23:59:59')->toDateTimeString()]);

            $auctions->whereBetween('auctions.published_date', [Carbon::parse(request()->start_date.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->end_date. ' 23:59:59')->toDateTimeString()]);

            $posting_items->whereBetween('posting_items.published_date', [Carbon::parse(request()->start_date.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->end_date. ' 23:59:59')->toDateTimeString()]);

            $order->whereBetween('orders.created_at', [Carbon::parse(request()->start_date.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->end_date. ' 23:59:59')->toDateTimeString()]);

            $order_pending->whereBetween('orders.created_at', [Carbon::parse(request()->start_date.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->end_date. ' 23:59:59')->toDateTimeString()]);
        }

        if(request()->store == 'all') {

            $user_store = UserStore::where('user_id', auth()->id())
                                ->get()
                                ->pluck('store_id')
                                ->toArray();

            $store = Store::where('store_company_code', request()->category == 'Auction' ? 'HASI' : 'HRH')->whereIn('id', $user_store)->get()->pluck('id')->toArray();

            $postings->whereIn('postings.store_id', $store);

            $auctions->whereIn('auctions.store_id', $store);

            $posting_items->whereIn('posting_items.store_id', $store);

            $order->whereIn('orders.store_id', $store);

            $order_pending->whereIn('orders.store_id', $store);

            $total_amount_this_monthly->whereIn('payments.store_id', $store);

            $total_amount_last_monthly->whereIn('payments.store_id', $store);

            $total_amount_yesterday->whereIn('payments.store_id', $store);

            $total_amount_today->whereIn('payments.store_id', $store);

            for ($i = 12; $i >= 1; $i--) {
                $total_amount_monthly[$i] = Payment::select([
                                \DB::raw('COALESCE(SUM(payments.total_amount), 0) as month_total_amount'),
                            ])
                            ->whereIn('payments.store_id', $store)
                            ->whereBetween('payments.created_at', [Carbon::create($year_now, $i)->startOfMonth()->toDateTimeString(), Carbon::create($year_now, $i)->endOfMonth()->toDateTimeString()])
                            ->pluck('month_total_amount');

            }

            for ($i = 1; $i <= 31; $i++) {

                $total_amount_per_day[$i] = Payment::select([
                                \DB::raw('COALESCE(SUM(payments.total_amount), 0) as day_total_amount'),
                            ])
                            ->whereIn('payments.store_id', $store)
                            ->whereBetween('payments.created_at', [Carbon::create($year_now, $month_now, $i)->startOfDay()->toDateTimeString(), Carbon::create($year_now, $month_now, $i)->endOfDay()->toDateTimeString()])
                            ->pluck('day_total_amount');

            }

        }

        if(request()->store != 'all') {

            $postings->where('postings.store_id', request()->store ? request()->store : session()->get('store_id'));

            $auctions->where('auctions.store_id', request()->store ? request()->store : session()->get('store_id'));

            $posting_items->where('posting_items.store_id', request()->store ? request()->store : session()->get('store_id'));

            $order->where('orders.store_id', request()->store ? request()->store : session()->get('store_id'));

            $order_pending->where('orders.store_id', request()->store ? request()->store : session()->get('store_id'));

            $total_amount_this_monthly->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'));

            $total_amount_last_monthly->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'));

            $total_amount_yesterday->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'));

            $total_amount_today->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'));

            for ($i = 12; $i >= 1; $i--) {
                $total_amount_monthly[$i] = Payment::select([
                                \DB::raw('COALESCE(SUM(payments.total_amount), 0) as month_total_amount'),
                            ])
                            ->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'))
                            ->whereBetween('payments.created_at', [Carbon::create($year_now, $i)->startOfMonth()->toDateTimeString(), Carbon::create($year_now, $i)->endOfMonth()->toDateTimeString()])
                            ->pluck('month_total_amount');

            }

            for ($i = 1; $i <= 31; $i++) {

                $total_amount_per_day[$i] = Payment::select([
                                \DB::raw('COALESCE(SUM(payments.total_amount), 0) as day_total_amount'),
                            ])
                            ->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'))
                            ->whereBetween('payments.created_at', [Carbon::create($year_now, $month_now, $i)->startOfDay()->toDateTimeString(), Carbon::create($year_now, $month_now, $i)->endOfDay()->toDateTimeString()])
                            ->pluck('day_total_amount');

            }

        }

        $payment = Payment::select([
            \DB::raw('SUM(payments.total_amount) as sub_total_amount'),
            \DB::raw($order->count().' as total_order'),
            \DB::raw($postings->count().' as total_posted'),
            \DB::raw($posting_items->count().' as total_posting_items'),
            \DB::raw($auctions->count().' as total_auctions'),
            \DB::raw($order_pending->count().' as total_pending'),
            \DB::raw($total_amount_this_monthly->first().' as total_amount_this_monthly'),
            \DB::raw($total_amount_last_monthly->first().' as total_amount_last_monthly'),
            \DB::raw($total_amount_yesterday->first().' as total_amount_yesterday'),
            \DB::raw($total_amount_today->first().' as total_amount_today'),
            \DB::raw($total_amount_monthly[1]->first().' as total_amount_monthly_january'),
            \DB::raw($total_amount_monthly[2]->first().' as total_amount_monthly_fabruary'),
            \DB::raw($total_amount_monthly[3]->first().' as total_amount_monthly_march'),
            \DB::raw($total_amount_monthly[4]->first().' as total_amount_monthly_april'),
            \DB::raw($total_amount_monthly[5]->first().' as total_amount_monthly_may'),
            \DB::raw($total_amount_monthly[6]->first().' as total_amount_monthly_june'),
            \DB::raw($total_amount_monthly[7]->first().' as total_amount_monthly_july'),
            \DB::raw($total_amount_monthly[8]->first().' as total_amount_monthly_august'),
            \DB::raw($total_amount_monthly[9]->first().' as total_amount_monthly_september'),
            \DB::raw($total_amount_monthly[10]->first().' as total_amount_monthly_october'),
            \DB::raw($total_amount_monthly[11]->first().' as total_amount_monthly_november'),
            \DB::raw($total_amount_monthly[12]->first().' as total_amount_monthly_december'),
            \DB::raw($total_amount_per_day[1]->first().' as total_amount_per_day_1'),
            \DB::raw($total_amount_per_day[2]->first().' as total_amount_per_day_2'),
            \DB::raw($total_amount_per_day[3]->first().' as total_amount_per_day_3'),
            \DB::raw($total_amount_per_day[4]->first().' as total_amount_per_day_4'),
            \DB::raw($total_amount_per_day[5]->first().' as total_amount_per_day_5'),
            \DB::raw($total_amount_per_day[6]->first().' as total_amount_per_day_6'),
            \DB::raw($total_amount_per_day[7]->first().' as total_amount_per_day_7'),
            \DB::raw($total_amount_per_day[8]->first().' as total_amount_per_day_8'),
            \DB::raw($total_amount_per_day[9]->first().' as total_amount_per_day_9'),
            \DB::raw($total_amount_per_day[10]->first().' as total_amount_per_day_10'),
            \DB::raw($total_amount_per_day[11]->first().' as total_amount_per_day_11'),
            \DB::raw($total_amount_per_day[12]->first().' as total_amount_per_day_12'),
            \DB::raw($total_amount_per_day[13]->first().' as total_amount_per_day_13'),
            \DB::raw($total_amount_per_day[14]->first().' as total_amount_per_day_14'),
            \DB::raw($total_amount_per_day[15]->first().' as total_amount_per_day_15'),
            \DB::raw($total_amount_per_day[16]->first().' as total_amount_per_day_16'),
            \DB::raw($total_amount_per_day[17]->first().' as total_amount_per_day_17'),
            \DB::raw($total_amount_per_day[18]->first().' as total_amount_per_day_18'),
            \DB::raw($total_amount_per_day[19]->first().' as total_amount_per_day_19'),
            \DB::raw($total_amount_per_day[20]->first().' as total_amount_per_day_20'),
            \DB::raw($total_amount_per_day[21]->first().' as total_amount_per_day_21'),
            \DB::raw($total_amount_per_day[22]->first().' as total_amount_per_day_22'),
            \DB::raw($total_amount_per_day[23]->first().' as total_amount_per_day_23'),
            \DB::raw($total_amount_per_day[24]->first().' as total_amount_per_day_24'),
            \DB::raw($total_amount_per_day[25]->first().' as total_amount_per_day_25'),
            \DB::raw($total_amount_per_day[26]->first().' as total_amount_per_day_26'),
            \DB::raw($total_amount_per_day[27]->first().' as total_amount_per_day_27'),
            \DB::raw($total_amount_per_day[28]->first().' as total_amount_per_day_28'),
            \DB::raw($total_amount_per_day[29]->first().' as total_amount_per_day_29'),
            \DB::raw($total_amount_per_day[30]->first().' as total_amount_per_day_30'),
            \DB::raw($total_amount_per_day[31]->first().' as total_amount_per_day_31'),
        ]);

        if(request()->store == 'all') {
            $payment->whereIn('payments.store_id', $store);
        }

        if(request()->store != 'all') {
            $payment->where('payments.store_id', request()->store ? request()->store : session()->get('store_id'));
        }


        if(!request()->start_ending) {

            $payment->where('payments.created_at', 'LIKE', "%{$date_now}%");
        }

        if(request()->start_ending) {

            $payment->whereBetween('payments.created_at', [Carbon::parse(request()->start_date.' 00:00:00')->toDateTimeString(), Carbon::parse(request()->end_date. ' 23:59:59')->toDateTimeString()]);
        }

        return $payment;

	}
}
