<?php

namespace App\Http\Controllers;

use App\Helpers\ModelDecrypter;
use App\Models\CustomerRegistration;

use Illuminate\Http\Request;

class CustomerRegistrationController extends Controller
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
    public function index()
    {
        $this->authorize('list', CustomerRegistration::class);

        $query = CustomerRegistration::selectedFields()
                ->whereNull('verified_date')
                ->sortable()
                ->searchable()
                ->orderBy('created_at', 'desc');


        $response = $this->response($query);

            (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($customer) {
                $customer =   (new ModelDecrypter)->decryptModel($customer);
            });

        return $response;


    }
}
