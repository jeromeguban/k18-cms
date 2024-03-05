<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BidderDeposit;
use App\Models\Customer;
use App\Helpers\ModelDecrypter;
use Illuminate\Support\Facades\Redis;

class BidderDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $this->authorize('list', BidderDeposit::class);

        $query = BidderDeposit::selectedFields()
                    ->joinCustomer()
                    // ->joinCustomerLoginCredential()
                    ->joinPaymentType()
                    ->joinCreatedBy()
                    ->withRelations()
                    // ->sortable()
                    ->searchable()
                    ->orderBy('bidder_deposits.created_at', 'desc');

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($bidder_deposit) {
            $bidder_deposit =   (new ModelDecrypter)->decryptModel($bidder_deposit);
        });

       return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', BidderDeposit::class);

        $request->validate([
            'customer_id'       => 'required',
            'payment_type_id'   => 'required',
            'deposit_amount'    => 'required',
            'payment_status'    => 'required'
        ], [], [
            'customer_id'       => 'Customer',
            'payment_type_id'   => 'Payment Type',
            'deposit_amount'    => 'Deposit Amount',
            'payment_status'    => 'Payment Status',
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        $bidder_deposit = BidderDeposit::create([
            'customer_id'    => $request->customer_id,
            'payment_type_id'=> $request->payment_type_id,
            'status'         => 'Deposit', 
            'reference_code' => 'BD-'.strtoupper(\Str::random(7)),
            'deposit_amount' => $request->deposit_amount,
            'payment_status' => $request->payment_status,
            'deposit_type'   => "Onsite",
            'created_by'     => auth()->id(),
            'modified_by'    => auth()->id(),
        ]);

        $bidder_deposits = BidderDeposit::select([
                                    \DB::raw('SUM(deposit_amount) as total_deposit_amount')
                                ])
                                ->where('customer_id', $request->customer_id)
                                ->wherePaid()
                                ->whereDeposit()
                                ->first();

        $total_bidder_deposit = $bidder_deposits ? (float) $bidder_deposits->total_deposit_amount : 0;

        Redis::set("customer:{$customer->customer_id}:bid_deposit", $total_bidder_deposit);

        activity()
            ->performedOn( $bidder_deposit )
            ->log('Bidder has been deposit to auction');

        return [
            'success'   => 1,
            'data'      => $bidder_deposit
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
