<?php

namespace App\Http\Controllers;

use App\Models\AccountNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccountNumberController extends Controller
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountNumber  $account_number
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountNumber $account_number)
    {
        $this->authorize('update', $account_number);

        $request->validate([
            'account_number'  => [
                'required',
                Rule::unique('account_numbers', 'account_number')->ignore($account_number->id)->where(function ($query) use ($account_number) {
                    return $query->where('bank_id', $account_number->bank_id);
                })],
            'account_name' => 'nullable|max:191'], 
            [], 
            [
                'account_number'  => 'Account Number',
                'account_name'    => 'Account Name'
            ]
        );

        $account_number->update([
            'account_number'    => request()->account_number,
            'account_name'      => request()->account_name,
            'modified_by'       => auth()->id(),
        ]);


        activity()
            ->performedOn($account_number)
            ->withProperties($account_number)
            ->log('Account Number has been updated');

        return [
            'success'   => 1,
            'data'      => $account_number
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountNumber  $account_number
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountNumber $account_number)
    {
        $this->authorize('delete', $account_number);

        DB::transaction(function () use ($account_number) {

            $account_number->update([
                'deleted_by'    => auth()->id()
            ]);

            $account_number->delete();

            activity()
                ->performedOn($account_number)
                ->withProperties($account_number)
                ->log('Account Number has been deleted');
        });

        return [
            'success'   => 1
        ];
    }
}
