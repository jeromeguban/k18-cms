<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Processes\CustomerProcess;
use App\Helpers\ModelDecrypter;
use DB;

class CustomerController extends Controller
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
        $this->authorize('list', Customer::class);

        $query = Customer::selectedFields()
                ->joinLoginCredential()
                ->joinAddress()
                ->whereQueryScopes()
                ->withRelations()
                ->sortable()
                ->searchable();


        if(request()->filter == 'last_name')
            $query->where('customers.customer_lastname_index', md5(strtoupper(request()->search_customer)));

        if(request()->filter == 'first_name')
            $query->where('customers.customer_firstname_index', md5(strtoupper(request()->search_customer)));

        if(request()->filter == 'email')
            $query->where('customer_login_credentials.email_index', md5(request()->search_customer));

        if(request()->filter == 'mobile_no')
            $query->where('customer_login_credentials.mobile_no_index', md5(request()->search_customer));

       $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($customer) {
            $customer =   (new ModelDecrypter)->decryptModel($customer);
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
    public function store(CustomerRequest $request, CustomerProcess $process)
    {
       $this->authorize('create', Customer::class);

        $process->create();

        activity()
            ->performedOn( $process->customer() )
            ->withProperties($process->customer())
            ->log('Customer has been created');

        return [
            'success'   => 1,
            'data'      => $process->customer()
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
        $this->authorize('view', Customer::findOrFail($id));

        $query= Customer::selectedFields()
            ->joinLoginCredential()
            ->joinAddress()
            ->where('customers.customer_id', $id)
            ->withRelations();

        return (new ModelDecrypter)->decryptCollection($query->get())->toJson();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $formRequest, CustomerProcess $process, $id)
    {
        $this->authorize('update', Customer::findOrFail($id));

        $process->find($id)->update();

        activity()
            ->performedOn( $process->customer() )
            ->withProperties($process->customer())
            ->log('Customer has been updated');

        return [
            'success'   => 1,
            'data'      => $process->customer()
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerProcess $process, $id)
    {
        $this->authorize('delete', Customer::findOrFail($id));

        $process->find($id)->delete();

        activity()
            ->performedOn( $process->customer() )
            ->withProperties($process->customer())
            ->log('Customer has been deleted');

        return [ 'success'   => 1 ];
    }
}
