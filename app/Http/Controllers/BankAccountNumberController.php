<?php

namespace App\Http\Controllers;

use App\Models\AccountNumber;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BankAccountNumberController extends Controller
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
    public function index(Bank $bank)
    {
        $this->authorize('list', AccountNumber::class);

        $query = AccountNumber::joinBank()
            ->searchable()
            ->sortable()
            ->where('account_numbers.bank_id', $bank->id);

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Bank $bank)
    {
        $this->authorize('create', AccountNumber::class);

        $request->validate([
            'account_number'    => [
                'required',
                Rule::unique('account_numbers', 'account_number')->where(function ($query) use ($bank) {
                    return $query->where('bank_id', $bank->id);
                })
            ],
            'account_name' => 'nullable|max:191'
        ], [], [
            'account_number'  => 'Account Number',
            'account_name'    => 'Account Name'
        ]);


        $account_number = AccountNumber::create([
            'bank_id'           => $bank->id,
            'account_number'    => request()->account_number,
            'account_name'      => request()->account_name,
            'created_by'        => auth()->id(),
            'modified_by'       => auth()->id(),
        ]);


        activity()
            ->performedOn($account_number)
            ->log('Account Number has been created');

        return [
            'success'   => 1,
            'data'      => $account_number
        ];
    }
}
