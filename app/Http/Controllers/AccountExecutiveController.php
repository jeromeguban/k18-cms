<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountExecutiveRequest;
use App\Models\AccountExecutive;
use App\Processes\AccountExecutiveProcess;
use DB;
use Illuminate\Http\Request;

class AccountExecutiveController extends Controller
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
        $this->authorize('list', AccountExecutive::class);

        $query = AccountExecutive::selectedFields()
            ->leftJoinUser()
            ->withRelations()
            ->searchable()
            ->sortable();

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
    public function store(AccountExecutiveRequest $request, AccountExecutiveProcess $process)
    {

        $this->authorize('create', AccountExecutive::class);

        $process->create();

        activity()
            ->performedOn($process->accountExecutive())
            ->withProperties($process->accountExecutive())
            ->log('Account Executive has been created');

        return [
            'data' => $process->accountExecutive(),
            'success' => 1,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountExecutive  $account_executive
     * @return \Illuminate\Http\Response
     */
    public function show(AccountExecutive $account_executive)
    {

        $this->authorize('view', $account_executive);

        return AccountExecutive::select([
            'account_executives.*',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.phone',
        ])
            ->join('users', 'users.id', '=', 'account_executives.user_id')
            ->withRelations()
            ->where('id', $account_executive->id)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountExecutive  $account_executive
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountExecutive $account_executive)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountExecutive  $account_executive
     * @return \Illuminate\Http\Response
     */
    public function update(AccountExecutiveProcess $process, AccountExecutiveRequest $request, AccountExecutive $account_executive)
    {

        $this->authorize('update', $account_executive);

        $process->find($account_executive->id)
            ->update();

        activity()
            ->performedOn($process->accountExecutive())
            ->withProperties($process->accountExecutive())
            ->log('Account Executive has been updated');

        return [
            'data' => $process->accountExecutive(),
            'success' => 1,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountExecutive  $account_executive
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountExecutive $account_executive)
    {

        $this->authorize('delete', $account_executive);

        DB::transaction(function () use ($account_executive) {

            activity()
                ->performedOn($account_executive->user)
                ->log('Account Executive User Account has been deleted');

            $account_executive->update([
                'deleted_by' => auth()->id(),
            ]);

            $account_executive->delete();

            activity()
                ->performedOn($account_executive)
                ->withProperties($account_executive)
                ->log('Account Executive has been deleted');
        });

        return [
            'success' => 1,
        ];
    }
}
