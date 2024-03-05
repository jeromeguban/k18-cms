<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BankController extends Controller
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
        $this->authorize('list', Bank::class);

        $query = Bank::withRelations()
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
    public function store(Request $request)
    {

        $request->validate([
            'name'        => 'required|unique:banks|max:191',
            'description' => 'nullable|max:191'
        ]);

        $bank = Bank::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id(),
        ]);

        activity()
            ->performedOn($bank)
            ->withProperties($bank)
            ->log('Bank has been created');

        return [
            'success' => 1,
            'data' => $bank
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        $this->authorize('view', $bank);

        return Bank::where('id', $bank->id)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        //
    }

    public function update(Request $request, Bank $bank)
    {
        $this->authorize('update', $bank);

        $request->validate([
            'name' => [
                'required',
                Rule::unique('banks')->ignore($bank->id),
            ],
            'description' => 'nullable|max:191'
        ]);

        $bank->update([
            'name'          => $request->name,
            'description'   => $request->description,
            'modified_by'   => auth()->id(),
        ]);

        activity()
            ->performedOn($bank)
            ->withProperties($bank)
            ->log('Bank has been updated');

        return [
            'success' => 1,
            'data' => $bank
        ];
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $this->authorize('delete', $bank);

        $bank->delete();

        activity()
            ->performedOn($bank)
            ->withProperties($bank)
            ->log('Bank has been deleted');

        return ['success' => 1];
    }
}
