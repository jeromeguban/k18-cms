<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', Cart::class);

        $query = Cart::selectedFields()
            ->addSelect([
                'postings.auction_number',
                DB::raw('carts.price * carts.quantity as total_price'),
                DB::raw('IF(postings.lot_number IS NOT NULL, postings.lot_number, "N/A") as lot_number')]
            )
            ->joinStore()
            ->joinPosting()
            ->where('carts.category', 'Auction')
            ->withRelations()
            ->searchable()
            ->sortable();

        $query->where('postings.store_id', session()->get('store_id'));

        if(request()->customer_id)
            $query->where('carts.customer_id', request()->customer_id);

        if(request()->auction_id)
            $query->where('postings.auction_id', request()->auction_id);

        return $this->response($query);
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
        //
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
    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $cart->update([
            'expires_at'  => Carbon::now()->endOfDay()->addDay()
        ]);

        activity()
            ->performedOn($cart)
            ->withProperties($cart)
            ->log('Cart has been updated');

        return [
            'sucess' => 1,
            'data' => $cart
        ];
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
