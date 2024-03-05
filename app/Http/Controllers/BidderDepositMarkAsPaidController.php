<?php

namespace App\Http\Controllers;

use App\Models\BidderDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class BidderDepositMarkAsPaidController extends Controller
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BidderDeposit $bidder_deposit)
    {
        
        $this->authorize('update', $bidder_deposit);

        $bidder_deposit->update([
            'modified_by'    => auth()->id(),
            'payment_status' => 'Paid'
        ]);

        // $bidder_deposits = BidderDeposit::select([
        //                             \DB::raw('SUM(deposit_amount) as total_deposit_amount')
        //                         ])
        //                         ->where('customer_id', $bidder_deposit->customer_id)
        //                         ->wherePaid()
        //                         ->whereDeposit()
        //                         ->first();

        // $total_bidder_deposit = $bidder_deposits ? (float) $bidder_deposits->total_deposit_amount : 0;

        // Redis::set("customer_{$bidder_deposit->customer_id}_bid_deposit", $total_bidder_deposit);

        activity()
            ->performedOn( $bidder_deposit )
            ->withProperties($bidder_deposit)
            ->log('Bidder Deposit mark as paid');

        return [
            'success'   => 1,
            'data'      => $bidder_deposit->refresh()
        ];

    }
}
