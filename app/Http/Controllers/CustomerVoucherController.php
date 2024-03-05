<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Voucher;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;
use App\Models\CustomerVoucher;

class CustomerVoucherController extends Controller
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
    public function index(Voucher $voucher)
    {
        $this->authorize('list', Customer::class);

        $voucher_customer = CustomerVoucher::where('voucher_id', $voucher->id)->first();

        if (!$voucher_customer){

            abort(422, "No Data Found!");
        }

            $query = Customer::selectedFields()
                ->where('customer_id', $voucher_customer->customer_id);

            $response = $this->response($query);

            (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($customer) {
                $customer =   (new ModelDecrypter)->decryptModel($customer);
            });

            return $response;   
        
    }
}
