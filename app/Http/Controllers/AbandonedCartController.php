<?php

namespace App\Http\Controllers;

use App\Helpers\ModelDecrypter;
use App\Models\AbandonedCartHistory;
use App\Models\Cart;
use App\Models\PostingItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Exports\AbandonCartExport;

class AbandonedCartController extends Controller
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
        $this->authorize('list', Cart::class);

        $query = Cart::selectedFields()
            ->joinPosting()
            ->joinPostingItem()
            ->joinCustomer()
            ->joinCustomerLoginCredential()
            ->where('carts.category', 'Retail')
            ->where('carts.expires_at', '<=', now()->toDateTimeString())
            ->where('carts.store_id', session()->get('store_id'))
            ->withRelations()
            ->searchable()
            ->sortable();

        if (request()->customer_id) {
            $query->where('carts.customer_id', request()->customer_id);
        }
        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($cart) {
            $cart = (new ModelDecrypter)->decryptModel($cart);
        });

        return $response;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Cart $cart)
    {

        $this->authorize('create', AbandonedCartHistory::class);

        DB::transaction(function () use ($request, $cart) {

            AbandonedCartHistory::create([
                'customer_id' => $cart->customer_id,
                'store_id' => $cart->store_id,
                'details' => json_encode($cart->toArray()),
                'status' => $request->status,
                'remarks' => $request->remarks,
                'created_by' => auth()->id(),
                'modified_by' => auth()->id(),
            ]);

            Redis::connection('hmr-local-redis')->del("item:{$cart->posting_item_id}:cart:" . $cart->customer_id);
            $cart->delete();

            activity()
                ->performedOn($cart)
                ->withProperties($cart)
                ->log('Cart Successfully Cancelled');

        });

        return [
            'success' => 1,
        ];

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

        $posting_item = PostingItem::where('posting_id', $cart->posting_id)
            ->where('store_id', session()->get('store_id'))
            ->where('id', $cart->posting_item_id)
            ->first();

        $posting_cart_keys = Redis::connection('hmr-local-redis')->keys("item:{$posting_item->id}:cart:*");
        $posting_cart_cache = count($posting_cart_keys) ? Redis::connection('hmr-local-redis')->mget($posting_cart_keys) : [];
        $total_quantity_in_cart = collect($posting_cart_cache)->sum() ?? 0;

        $current_stock_quantity = $posting_item->quantity - $total_quantity_in_cart;

        abort_if($this->getExistingPostingQuantityInCart($posting_item, $cart) > $posting_item->quantity, 401, "Only $current_stock_quantity left in stock.");

        DB::transaction(function () use ($cart, $posting_item, $request) {

            AbandonedCartHistory::create([
                'customer_id' => $cart->customer_id,
                'store_id' => $cart->store_id,
                'details' => json_encode($cart->toArray()),
                'status' => "Return to Cart",
                'remarks' => $request->remarks,
                'created_by' => auth()->id(),
                'modified_by' => auth()->id(),
            ]);

            Redis::connection('hmr-local-redis')->set("item:{$posting_item->id}:cart:" . auth()->user()->customer_id,
                $this->getExistingPostingQuantityInCart($posting_item, $cart),
                "EX", env('CART_EXPIRY', 30) * 60//Expiry based on minutes
            );

            $cart->update([
                'expires_at' => Carbon::now()->addMinutes(30),
            ]);
        });

        activity()
            ->performedOn($cart)
            ->withProperties($cart)
            ->log('Item Successfully Transfer to Cart');

        return [
            'sucess' => 1,
            'data' => $cart,
        ];
    }

    private function getExistingPostingQuantityInCart($posting_item, $cart)
    {
        $existing_posting_quantity_in_cart = Redis::connection('hmr-local-redis')->get("item:{$posting_item->id}:cart:" . $posting_item->customer_id) ?? 0;

        // if (request()->type == 'inc') {
        //     return $existing_posting_quantity_in_cart += 1;
        // }

        // if (request()->type == 'dec') {
        //     return $existing_posting_quantity_in_cart -= 1;
        // }

        return $existing_posting_quantity_in_cart += $cart->quantity;
    }

    public function exportData()
    {
        return \Excel::download(new AbandonCartExport,
            'Abandon Cart Report - '.now()->toDateTimeString().'.xlsx');
    }
}
