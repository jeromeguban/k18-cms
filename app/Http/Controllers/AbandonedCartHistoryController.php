<?php

namespace App\Http\Controllers;

use App\Helpers\ModelDecrypter;
use App\Models\AbandonedCartHistory;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbandonedCartHistoryController extends Controller
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
        $this->authorize('list', AbandonedCartHistory::class);

        $query = AbandonedCartHistory::selectedFields()
            ->joinCustomer()
            ->joinCustomerLoginCredential()
            ->where('abandoned_cart_histories.store_id', session()->get('store_id'))
            ->withRelations()
            ->searchable()
            ->sortable();

        if (request()->customer_id) {
            $query->where('abandoned_cart_histories.customer_id', request()->customer_id);
        }
        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($cart) {
            $cart = (new ModelDecrypter)->decryptModel($cart);
        });

        return $response;

        // $query = AbandonedCartHistory::selectedFields()->first();
        // $abandoned_cart_history_details = json_decode($query->details);

        // dd($abandoned_cart_history_details);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, AbandonedCartHistory $abandoned_cart_history)
    {

        $this->authorize('update', $abandoned_cart_history);

        DB::transaction(function () use ($request, $abandoned_cart_history) {

            $abandoned_cart_history_details = json_decode($abandoned_cart_history->details, true);

            Cart::create([
                'customer_id' => $abandoned_cart_history->customer_id,
                'store_id' => $abandoned_cart_history->store_id,
                'posting_id' => $abandoned_cart_history_details['posting_id'],
                'posting_item_id' => $abandoned_cart_history_details['posting_item_id'],
                'quantity' => $abandoned_cart_history_details['quantity'],
                'price' => $abandoned_cart_history_details['price'],
                'referral_code' => $abandoned_cart_history_details['referral_code'],
                'affiliate_marketing_id' => $abandoned_cart_history_details['affiliate_marketing_id'],
                'commission' => $abandoned_cart_history_details['commission'],
                'event_id' => $abandoned_cart_history_details['event_id'],
                'klaviyo_campaign_id' => $abandoned_cart_history_details['klaviyo_campaign_id'],
                'expires_at' => $abandoned_cart_history_details['expires_at'],
                'category' => $abandoned_cart_history_details['category'],
                'details' => $abandoned_cart_history_details['details'],
            ]);

            $abandoned_cart_history->delete();

            activity()
                ->performedOn($abandoned_cart_history)
                ->withProperties($abandoned_cart_history)
                ->log('Abandoned Cart History Successfully Undo');

        });

        return [
            'success' => 1,
        ];

    }
}
