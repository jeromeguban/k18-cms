<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BidderDeposit;
use App\Jobs\WithdrawBidderDepositJob;

class BidderDepositWithdrawController extends Controller
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

        \DB::transaction(function() use($bidder_deposit){

            $bidder_deposit->update([
                'modified_by'    => auth()->id(),
                'status'        => 'Withdraw'
            ]);

            $this->withdrawBidderDepositApiCallback($bidder_deposit);

            activity()
                ->performedOn( $bidder_deposit )
                ->withProperties($bidder_deposit)
                ->log('Bidder Deposit has been Withdraw');

            return [
                'success'   => 1,
                'data'      => $bidder_deposit->refresh()
            ];
            
        }); 

    }

    private function withdrawBidderDepositApiCallback($bidder_deposit)
	{	
		dispatch(new WithdrawBidderDepositJob($bidder_deposit));

		return $this;
	}
}
