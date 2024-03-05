<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Jobs\TermCreateJob;
use App\Jobs\TermDeleteJob;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Jobs\TermUpdateJob;

class TermController extends Controller
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
         $this->authorize('list', Term::class);

        $query = Term::withRelations()
            ->searchable()
            ->sortable();


        if(request()->type == 'voucher')
             $query->where('type', 'Voucher');

            
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
        $this->authorize('create', Term::class);

        $request->validate([
            'name'      => 'required|unique:terms|max:191',
            'terms'     => 'required',
            'type'      => 'required',
        ], [], [
            'name'  => 'Name',
            'terms' => 'Terms',
            'type'  => 'Type',
        ]);

        \DB::transaction(function() use($request){

            $term = Term::create([
                'name'          => $request->name,
                'terms'         => $request->terms,
                'type'          => $request->type,
                'created_by'    => auth()->id(),
                'modified_by'   => auth()->id(),
            ]);

            if($term->type != 'Voucher' || $term->type != 'Retail')
                TermCreateJob::dispatchSync($term, true);

            activity()
                ->performedOn( $term )
                ->withProperties($term)
                ->log('Term and Condition has been created');

            return [
                'success' => 1,
                'data' => $term
            ];

        });

       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
     public function show($id)
    {
        $this->authorize('view', Term::findOrFail($id));

        return Term::where('id', $id)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function edit(Term $term)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term)
    {
        $this->authorize('update', $term);

        $request->validate([
            'name'      => 'required|max:191',
            'terms'     => 'nullable',
            'type'      => 'required',
        ], [], [
            'name'  => 'Name',
            'terms' => 'Terms',
            'type'  => 'Type',
        ]);


        $term->update([
            'name'          => $request->name,
            'terms'         => $request->terms,
            'type'          => $request->type,
            'modified_by'   => auth()->id(),
        ]);

        if ($term->type != 'Voucher'|| $term->type != 'Retail')
            TermUpdateJob::dispatchSync($term, true);

        activity()
            ->performedOn( $term )
            ->withProperties($term)
            ->log('Term and Conditions has been updated');

        return [
            'success' => 1,
            'data' => $term
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $term)
    {
        $this->authorize('delete', $term);

        \DB::transaction(function() use ($term) {

            if ($term->type != 'Voucher' || $term->type != 'Retail')
                TermDeleteJob::dispatchSync($term, true);

            $term->update([
                'deleted_by'    => auth()->id()
            ]);

            $term->delete();

            activity()
                ->performedOn( $term )
                ->withProperties($term)
                ->log('Term and Conditions has been deleted');
        });


        return [
            'success'   => 1
        ];
    }
}
