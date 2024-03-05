<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Processes\CustomerProcess;
use App\Helpers\ModelDecrypter;
use DB;

class CustomerResetPasswordController extends Controller
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
    public function update(CustomerRequest $formRequest, CustomerProcess $process, $id)
    {
        $this->authorize('update', Customer::findOrFail($id));

        $process->find($id)->passwordReset();

        activity()
            ->performedOn($process->customer())
            ->withProperties($process->customer())
            ->log('Customer has been updated');

        return [
            'success'   => 1,
            'data'      => $process->customer()
        ];
    }



}
